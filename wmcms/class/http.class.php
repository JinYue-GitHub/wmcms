<?php
/**
* http请求操作类
*
* @version        $Id: http.class.php 2015年8月9日 16:38 weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2022年04月02日 21:19 weimeng
*/
class http
{
	//ua
	public $ua;
	//host
	public $host;
	//超时时间
	public $timeout = 20;
	//重试次数
	public $retry = 0;
	//当前次数
	public $nowRetry = 0;
	//header
	public $header = array();

	
	function __construct($type='web')
	{
		$this->SetUa($type);
	}

	
	/**
	 * 模拟浏览器UA
	 * @param 参数1，必须。浏览器ua类型或者ua
	 */
	function SetUa( $type )
	{
		if( $type == 'm' )
		{
			$ua = "Mozilla/5.0 (iPhone; U; CPU like Mac OS X) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/4A93 Safari/419.3";
		}
		else if( $type == 'baidu' )
		{
			$ua = 'Baiduspider+(+http://www.baidu.com/search/spider.htm)';
		}
		else if( $type == 'web' || $type == '')
		{
			$ua = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36";
		}
		else
		{
			$ua = $type;
		}
		$this->ua = 'User-Agent:'.$ua;
	}
	
	
	/**
	 * 设置域名
	 * @param 参数1，必须，服务器域名
	 */
	function SetHost($host = '')
	{
		//host为空就查询hosts
		if( $host == '' )
		{
			if(PHP_OS == 'WINNT')
			{
				define('HOSTS_FILE_PATH', 'C:/Windows/System32/drivers/etc/hosts');
			}
			else if(in_array(PHP_OS, array('Linux','Darwin','FreeBSD','OpenBSD','WIN32','Windows','Unix')))
			{
				define('HOSTS_FILE_PATH', '/etc/hosts');
			}
			$hosts = file_get_contents(HOSTS_FILE_PATH);
			//去掉hosts的注释
			$hosts = preg_replace("/#.*?\r\n/",'',$hosts);
			//匹配每行
			$hostsArr = explode("\r\n", $hosts);
			if($hostsArr)
			{
				foreach ($hostsArr as $k=>$v)
				{
					if($v != '' )
					{
						//匹配ip和指向域名
						list($ip,$domain) = explode('|', preg_replace("/\\s+/",'|',$v));
						//如果存在就设置host为ip
						if( $domain == $_SERVER['HTTP_HOST'] )
						{
							$this->host = $domain;
							return $ip;
						}
					}
				}
			}
			return $_SERVER['HTTP_HOST'];
		}
		else
		{
			$this->host = $host;
		}
	}

	//设置头部信息
	function SetHeader($header)
	{
	    $this->header = $header;
	}
	
	//获取头部信息
	function GetHeader()
	{
	    $header = $this->header;
		$header[] = "Host:".$this->host;
		$header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";
		//$header[] = "Accept-Encoding:gzip, deflate, sdch";
		$header[] = "Accept-Language:zh-CN,zh;q=0.8";
		$header[] = "Cache-Control:max-age=0";
		$header[] = "Connection:keep-alive";
		$header[] = "Upgrade-Insecure-Requests:1";
		$header[] = $this->ua;
		return $header;
	}
	
	/**
	 * 获得url的源码
	 * @param 参数1，必须，url
	 * @param 参数2，选填，POST参数。
	 * @param 参数2，cookie_save  是否保存cookie
	 * @param 参数2，cookie_send  是否发送cookie
	 * @param 参数2，cookie_name  是否发送cook的名字
	 */
	function GetUrl($url , $data='')
	{
		//如果域名为空就设置
		if( $this->host == '' )
		{
			$this->SetHost(GetDomain($url , false));
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->GetHeader());
		curl_setopt($ch, CURLOPT_TIMEOUT,$this->timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//https请求支持
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		
		//设置curl是否保存或者发送cookie过去
		if(GetKey($data, 'cookie_save') == '1' || GetKey($data, 'cookie_send') == '1')
		{
			if(GetKey($data, 'cookie_name') != '')
			{
				$cookieFile = WMCACHE.'cookie_'.$data['cookie_name'].'.txt';
			}
			else
			{
				$cookeName = md5(basename(__FILE__,".php"));
				$cookieFile = WMCACHE.'cookie_'.$cookeName.'.txt';
			}
			//是否保存cookie
			if(GetKey($data, 'cookie_save')== '1')
			{
				curl_setopt($ch, CURLOPT_COOKIEJAR,$cookieFile);  
			}
			//是否需要发送cookie
			else if(GetKey($data, 'cookie_send') == '1')
			{
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);   
			}
		}
		//如果是post请求
		if( !empty($data) )
		{
			curl_setopt($ch, CURLOPT_POST, 0);
			//数据就转换
			if(is_array($data) )
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			}
			else
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}
		}
		
		$output = curl_exec($ch);
		//当前重试次数小于总共重试次数
		if ($output === FALSE && $this->nowRetry < $this->retry)
		{
			//重试
			$this->nowRetry = $this->nowRetry+1;
			$this->GetUrl($url , $data);
		}
		curl_close($ch);
		
		return $output;
	}
}
?>