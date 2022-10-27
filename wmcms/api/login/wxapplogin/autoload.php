<?php
require_once(WMCLASS.'weixin_app.class.php');
//wmcms整合登录类
class OtherLogin{
	private $wxappSer;
	private $auth;
	function __construct($data=array())
	{
		$this->appid = $data['appid'];
		$this->secret = $data['secret'];
		$this->auth = $data['auth'];
		$this->wxappSer = new WeiXin_App($data);
	}

	//获得跳转登录的url，qq登录是直接跳转。
	function GetJumpUrl()
	{
		return false;
	}

	
	/**
	 * 获得sessionkey
	 */
	function GetSessionKey()
	{
		return $this->wxappSer->GetSessionKey($this->auth['jscode']);
	}
	
	
	//获得用户信息
	function GetUserInfo($token='',$openid='')
	{
		$key = $this->auth['session_key'];
		$encryptedData = $this->auth['encryptedData'];
		$iv = $this->auth['iv'];
		$data = $this->wxappSer->DecryptData($encryptedData, $key, $iv);
		if( !isset($data['errmsg']) )
		{
			$data['type'] = '微信小程序';
			$data['nickname'] = $data['nickName'];
			$data['openid'] = $data['openId'];
			$data['unionid'] = isset($result['unionId'])?$result['unionId']:'';
		}
		return $data;
	}
}