<?php
/**
* 微信用户类
*
* @version        $Id: weixin_account.class.php 2017年8月3日 22:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
require_once 'weixin.class.php';

class WeiXin_Account extends WeiXin{
	static $openId;
	public $data;
	
	function __construct($data = array())
	{
		parent::__construct($data);
	}
	
	
	/**
	 * 获得用户openid
	 * @param 参数1，选填，1为静默授权方式，2为手动授权方式
	 */
	function GetOpenid($type = '1')
	{
		//静默授权方式
		if( $type == '1' )
		{
			$scope = 'snsapi_base';
		}
		//用户手动授权
		else
		{
			$scope = 'snsapi_userinfo';
		}

		//通过code获得openid
		if (!isset($_GET['code']))
		{
			//触发微信返回code码
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode(GetUrl(true)).'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
			Header("Location: $url");
			exit();
		}
		else
		{
			//创建获得openid的url
			$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->secret.'&code='.$_GET['code'].'&grant_type=authorization_code';
			$this->data = $this->__GetUrl($url);
			$openId = $this->data['openid'];
		}
		self::$openId = $openId;
		return $openId;
	}
	
	/**
	 * 生成微信授权url
	 * @param 参数1，选填，授权返回地址
	 */
	function CreateUrl($url='',$scope='snsapi_userinfo')
	{
		if( $url == '' )
		{
			$url = urlencode(GetUrl(true));
		}
		//触发微信返回code码
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$url.'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
		return $url;
	}
	
	/**
	 * 获得access_token
	 */
	function GetToken()
	{
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->secret.'&code='.$_GET['code'].'&grant_type=authorization_code';
		$data = $this->__GetUrl($url);
		return $data;
	}
	
	/**
	 * 获得用户信息
	 */
	function GetUserInfo($token='',$openid='')
	{
	    if( empty($token) || empty($openid) )
	    {
		    $result = $this->GetToken();
	    }
	    else
	    {
	        $result = array('access_token'=>$token,'openid'=>$openid);
	    }
		//验证成功
		if( !empty($result['access_token']) )
		{
			$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$result['access_token'].'&openid='.$result['openid'].'&grant_type=snsapi_userinfo';
			$this->data = $this->__GetUrl($url);
			return $this->data;
		}
		else
		{
			return $result;
		}
	}
}
?>