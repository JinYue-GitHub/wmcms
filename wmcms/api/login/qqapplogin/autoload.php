<?php
require_once(WMAPI."login/qqlogin/comm/config.php");
require_once(WMAPI."login/qqlogin/class/QC.class.php");

//wmcms整合登录类
class OtherLogin{
	private $qc;
	private $appid;
	private $apikey;
	private $backurl;
	private $auth;
	function __construct($data=array())
	{
		$this->appid = $data['appid'];
		$this->apikey = $data['apikey'];
		$this->backurl = $data['backurl'];
		$this->auth = isset($data['auth'])?$data['auth']:array();
		$this->qc = new QC();
	}

	//获得跳转登录的url。
	function GetJumpUrl()
	{
		return false;
	}
	
	
	//获得用户的信息
	function GetUserInfo($token='',$openid='')
	{
		$accessToken = $this->auth['access_token'];
		$openid = $this->auth['openid'];
		//使用access和openid交换用户信息
		$this->qc = new QC($accessToken,$openid,$this->appid);
		$infoarr = $this->qc->get_user_info();
		$data['type'] = '手机QQ';
		$data['openid'] = $openid;
		$data['nickname'] = $infoarr['nickname'];
		$data['unionid'] = isset($infoarr['unionid'])?$infoarr['unionid']:'';
		return $data;
	}
}
