<?php
if (!session_id()) session_start();
require_once(dirname(__FILE__)."/comm/config.php");
require_once(CLASS_PATH."QC.class.php");

//wmcms整合登录类
class OtherLogin{
	private $qc;
	private $appid;
	private $apikey;
	private $backurl;
	function __construct($data=array())
	{
		$this->appid = $data['appid'];
		$this->apikey = $data['apikey'];
		$this->backurl = $data['backurl'];
		$this->qc = new QC();
	}

	//获得跳转登录的url，qq登录是直接跳转。
	function GetJumpUrl()
	{
		$this->qc->qq_login($this->appid,$this->apikey,$this->backurl);
		die();
	}
	
	
	//获得用户的信息
	function GetUserInfo($token='',$openid='')
	{
		//获得access
		$access_token = $this->qc->qq_callback($this->appid,$this->apikey,$this->backurl);
		//获得openid
		$openid = $this->qc->get_openid($this->appid,$this->apikey,$this->backurl);
		
		//使用access和openid交换用户信息
		$this->qc = new QC($access_token,$openid,$this->appid);
		$infoarr = $this->qc->get_user_info();
		
		$data['type'] = 'QQ';
		$data['openid'] = $openid;
		$data['nickname'] = $infoarr['nickname'];
		$data['unionid'] = isset($infoarr['unionid'])?$infoarr['unionid']:'';
		return $data;
	}
}
