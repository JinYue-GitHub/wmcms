<?php
/**
* 云服务类
*
* @version        $Id: cloud.class.php 2017年2月22日 21:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class cloud{
	private $httpSer;
	private $email;
	private $appid;
	private $domain;
	
	/**
	 * 构造函数
	 * @param 参数1，必须，请求类型
	 * @param 参数1，必须，请求类型
	 */
	function __construct()
	{
		global $_SERVER;
		$this->httpSer = NewClass('http');
		$this->email = C('config.web.email');
		$this->appid = C('config.api.system.api_appid');
		$this->apikey = C('config.api.system.api_apikey');
		$this->secret = C('config.api.system.api_secretkey');
		$this->domain = TCP_TYPE.'://'.GetKey($_SERVER,'HTTP_HOST');
	}


	/**
	 * 请求获得参数
	 * @param 参数1，必须，请求参数
	 * @param 参数1，选填，附加请求数据
	 */
	function Request($url , $data = array())
	{
		$data['appid'] = $this->appid;
		$data['apikey'] = $this->apikey;
		$data['secret'] = $this->secret;
		$data['domain'] = $this->domain;
		$data['ver'] = WMVER;
		$htmlCode = $this->httpSer->GetUrl($url , $data);
		$code = @json_decode($htmlCode,true);
		return $code;
	}

	/**
	 * 获得最新的程序版本
	 * @param 参数1，必须，请求参数
	 */
	function GetNewVer()
	{
		return $this->Request(WMSERVER.'/index.php?m=version&c=version&a=getnew');
	}
	/**
	 * 获得程序版本列表
	 * @param 参数1，选填，是否是只查找能升级版本
	 */
	function GetVersionNext($isUpdate = 0)
	{
		$data['version'] = WMVER;
		return $this->Request(WMSERVER.'/index.php?m=version&c=version&a=getnext&isupdate='.$isUpdate , $data);
	}
	/**
	 * 写入升级记录
	 * @param 参数1，必填，旧的版本
	 * @param 参数2，必填，新的版本
	 */
	function SetUpdateLog($oldVer,$newVer)
	{
		$data['oldver'] = $oldVer;
		$data['newver'] = $newVer;
		return $this->Request(WMSERVER.'/index.php?m=version&c=version&a=setupdatelog' , $data);
	}
	function GetBasic()
	{
		$this->httpSer->timeout = 5;
		$data = $this->Request(WMSERVER.'/index.php?m=site&c=site&a=detail');
		if( empty($data['data']['content']) )
		{
			return Encrypt(time().'|WM|tpl::$tempContent = tpl::$tempCode;');
			return false;
		}
		else
		{
			return $data['data']['content'];
		}
	}
	
	

	/**
	 * 获得缓存的文件路径以及名字
	 * @param 参数1，必须，请求参数
	 */
	function ErrlogAdd($data)
	{
		$data['email'] = $this->email;
		$data['domain'] = $this->domain;
		return $this->Request(WMSERVER.'/index.php?m=errlog&c=errlog&a=add' , $data);
	}
	/**
	 * 获得BUG反馈的列表
	 * @param 参数1，必须，分类id，0为所有
	 * @param 参数2，选填，当前页数,默认为1
	 * @param 参数3，选填，每页数量。默认20
	 * @param 参数4，选填，是否只显示自己的数据
	 */
	function GetErrlogList($page=1,$pageCount = '20')
	{
		return $this->Request(WMSERVER.'/index.php?m=errlog&c=errlog&a=getlist&page='.$page.'&pagecount='.$pageCount);
	}
	

	/**
	 * 安装程序请求
	 */
	function Install()
	{
		return $this->Request(WMSERVER.'/index.php?m=tongji&c=install&version='.WMVER);
	}
	


	/**
	 * 获得BUG反馈的分类
	 */
	function GetMessageType()
	{
		return $this->Request(WMSERVER.'/index.php?m=message&c=messagetype&a=getlist');
	}
	/**
	 * 获得BUG反馈的列表
	 * @param 参数1，必须，分类id，0为所有
	 * @param 参数2，选填，当前页数,默认为1
	 * @param 参数3，选填，每页数量。默认20
	 * @param 参数4，选填，是否只显示自己的数据
	 */
	function GetMessageList($tid,$page=1,$pageCount = '20',$isUser=0)
	{
		return $this->Request(WMSERVER.'/index.php?m=message&c=message&a=getlist&page='.$page.'&tid='.$tid.'&pagecount='.$pageCount.'&isuser='.$isUser);
	}
	/**
	 * 获得BUG反馈的详情
	 * @param 参数1，必须，反馈ID
	 */
	function GetMessage($id)
	{
		return $this->Request(WMSERVER.'/index.php?m=message&c=message&a=getdetail&id='.$id);
	}
	/**
	 * 提交新的反馈内容
	 * @param 参数1，必须，反馈类型
	 * @param 参数2，必须，是否公开反馈
	 * @param 参数3，必须，反馈公开域名
	 * @param 参数4，必须，反馈内容
	 */
	function MessageAdd($tid,$open,$domainShow,$content)
	{
		$data['content'] = $content;
		$data['domain'] = $this->domain;
		$data['tid'] = $tid;
		$data['open'] = $open;
		$data['domainshow'] = $domainShow;
		
		return $this->Request(WMSERVER.'/index.php?m=message&c=message&a=add', $data);
	}
	

	/**
	 * 获得众研需求的列表
	 * @param 参数1，必须，需求状态，0为所有
	 * @param 参数2，选填，当前页数,默认为1
	 * @param 参数3，选填，每页数量。默认20
	 */
	function GetTogetherList($status,$page=1,$pageCount = '20')
	{
		return $this->Request(WMSERVER.'/index.php?m=together&c=together&a=getlist&page='.$page.'&status='.$status.'&pagecount='.$pageCount);
	}
	/**
	 * 获得众研需求的详情
	 * @param 参数1，必须，反馈ID
	 */
	function GetTogether($id)
	{
		return $this->Request(WMSERVER.'/index.php?m=together&c=together&a=getdetail&id='.$id);
	}
	/**
	 * 提交新的众研需求
	 * @param 参数1，必须，需求标题
	 * @param 参数2，必须，需求内容
	 */
	function TogetherAdd($domainShow , $title , $desc)
	{
		$data['domain_show'] = $domainShow;
		$data['domain'] = $this->domain;
		$data['title'] = $title;
		$data['desc'] = $desc;
		return $this->Request(WMSERVER.'/index.php?m=together&c=together&a=add', $data);
	}
	/**
	 * 众研需求互动操作
	 * @param 参数1，必须，需求id
	 * @param 参数2，必须，是否需求
	 */
	function TogetherOperation($id,$need)
	{
		$data['domain'] = $this->domain;
		$data['id'] = $id;
		$data['need'] = $need;
		return $this->Request(WMSERVER.'/index.php?m=together&c=together&a=operation', $data);
	}
	
	
	
	/**
	 * 获得应用信息
	 * @param 参数1，必须，应用唯一id标识
	 */
	function AppBuy($id)
	{
		$data['id'] = $id;
		return $this->Request('http://shop.'.WMDOMAIN.'/index.php?m=shop&c=app&a=detail', $data);
	}
	/**
	 * 应用下载完成事件
	 * @param 参数1，必须，应用地址
	 */
	function APPDownSuccess($md5)
	{
		$data['md5'] = $md5;
		return $this->Request('http://shop.'.WMDOMAIN.'/index.php?m=shop&c=down&a=downsuccess', $data);
	}
	/**
	 * 应用安装
	 * @param 参数1，必须，应用唯一id标识
	 */
	function APPInstall($id)
	{
		$data['id'] = $id;
		return $this->Request('http://shop.'.WMDOMAIN.'/index.php?m=shop&c=app&a=install', $data);
	}


	/**
	 * 检查应用最新版本
	 * @param 参数1，必须，应用唯一id标识
	 */
	function APPCheckUpdate($ids)
	{
		$data['ids'] = $ids;
		return $this->Request('http://shop.'.WMDOMAIN.'/index.php?m=shop&c=update&a=check', $data);
	}

	/**
	 * 应用更新版本
	 * @param 参数1，必须，应用唯一id标识
	 * @param 参数2，必须，应用版本号
	 */
	function APPUpdate($id,$appVer)
	{
		$data['id'] = $id;
		$data['app_ver'] = $appVer;
		return $this->Request('http://shop.'.WMDOMAIN.'/index.php?m=shop&c=update&a=plugin', $data);
	}
}
?>