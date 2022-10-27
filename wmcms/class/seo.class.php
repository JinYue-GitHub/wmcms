<?php
/**
* 搜索引擎SEO类
*
* @version        $Id: seo.class.php 2017年5月7日 20:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class seo{
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	


	/**
	 * 提交新的链接到搜索引擎
	 * @param 参数1，必须，搜索引擎的类型
	 * @param 参数2，必须，需要提交的url
	 */
	function UrlPost($type , $urls)
	{
		switch ($type)
		{
			case 'baidu':
				return $this->BaiDuUrls($urls);
				break;
		}
	}

	
	/**
	 * 提交新的链接到百度
	 * @param 参数1，必须，提交的网站域名
	 * @param 参数2，必须，需要提交的url
	 */
	function BaiDuUrls($urls)
	{
		$data = array();
		$domain = C('config.api.bdurl.api_appid');
		$token = C('config.api.bdurl.api_apikey');
		$api = 'http://data.zz.baidu.com/urls?site='.$domain.'&token='.$token;
		$ch = curl_init();
		$options =  array(
		    CURLOPT_URL => $api,
		    CURLOPT_POST => true,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POSTFIELDS => implode("\n", $urls),
		    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$code = curl_exec($ch);
		$result = @json_decode($code , true);
		if( !is_array($result) || empty($result) )
		{
			$data['code'] = 300;
			$data['message'] = '对不起，百度链接提交服务器暂时无法链接，请稍候再试！';
			return $data;
		}
		else if( isset($result['error']) )
		{
			switch ($result['error'])
			{
				case '400':
					if($result['message'] == 'site error' )
					{
						$data['message'] = '站点未在站长平台验证！';
					}
					else if($result['message'] == 'empty content' )
					{
						$data['message'] = 'post内容为空！';
					}
					else if($result['message'] == 'only 2000 urls are allowed once' )
					{
						$data['message'] = '每次最多只能提交2000条链接！';
					}
					else if($result['message'] == 'over quota' )
					{
						$data['message'] = '超过每日配额了，超配额后再提交都是无效的！';
					}
					break;
				case '401':
					$data['message'] = 'token错误！';
					break;
				case '404':
					$data['message'] = '接口地址填写错误！';
					break;
				case '500':
					$data['message'] = '服务器偶然异常，通常重试就会成功！';
					break;
			}
			$data['code'] = 300;
		}
		else
		{
			$count = count($urls);
			$data['data']['remain'] = $result['remain'];
			$data['data']['success'] = $result['success'];
			$data['data']['noturl'] = 0;
			$data['data']['not_same_site'] = 0;
			if( isset($result['not_same_site']) )
			{
				$data['data']['notlocal'] = count($result['not_same_site']);
			}
			if( isset($result['not_valid']) )
			{
				$data['data']['noturl'] = count($result['not_valid']);
			}
			$data['code'] = 200;
			$data['message'] = '共提交'.$count.'条url到百度。其中成功'.$result['success'].'条，失败'.($count-$result['success']).'条，其中不是本站链接有'.$data['data']['notlocal'].'条，不合法的链接有'.$data['data']['noturl'].'条，今日还剩余'.$result['remain'].'条！';
		}
		return $data;
	}
}
?>