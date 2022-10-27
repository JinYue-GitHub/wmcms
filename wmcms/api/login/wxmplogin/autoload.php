<?php
require_once(WMCLASS.'weixin_account.class.php');
//wmcms整合登录类
class OtherLogin extends WeiXin_Account
{
	function __construct($data=array())
	{
		parent::__construct($data);
		$this->appid = $data['appid'];
		$this->secret = $data['secret'];
		$this->backurl = $data['backurl'];
	}
	
	
	/**
	* 获得微信登录地址
	* @param 参数1，回调地址。
	*/
	function GetJumpUrl()
	{
		return parent::CreateUrl($this->backurl);
	}
	
	//获得用户的信息
	function GetUserInfo($token='',$openid='')
	{
		$result = parent::GetUserInfo();
		//验证成功
		if( GetKey($result,'openid') != '' )
		{
			$data['type'] = '微信';
			$data['openid'] = $result['openid'];
			$data['nickname'] = $result['nickname'];
			$data['unionid'] = isset($result['unionid'])?$result['unionid']:'';
			return $data;
		}
		else
		{
			tpl::ErrInfo($result['errmsg']);
		}
		
	}
}
