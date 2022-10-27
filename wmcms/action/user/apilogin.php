<?php
/**
* API登录请求处理
*
* @version        $Id: apilogin.php 2016年5月28日 22:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否是绑定
$bind = Request('bind');
//检查类型
$apiLoginType = str::ClearInclude(str::IsEmpty( Request('api') , $lang['user']['no_type']));
//如果是绑定类型并且需要检查登录
if( $bind == '1' )
{
    str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
}
//已经登录了
else
{
    str::RT( user::GetUid() , 0 , $lang['user']['islogin'] );    
}


//接口文件是否存在，存在就引入
if( !file_exists(WMCONFIG.'api.config.php') )
{
	tpl::ErrInfo($lang['user']['api_no']);
}

//接口参数设置
$appid = C('config.api.'.$apiLoginType.'.api_appid');
$apikey = C('config.api.'.$apiLoginType.'.api_apikey');
$secretkey = C('config.api.'.$apiLoginType.'.api_secretkey');
$open = C('config.api.'.$apiLoginType.'.api_open');
if( $open == '0' || empty($open) )
{
	tpl::ErrInfo($lang['user']['api_close']);
}

//定义回调地址
$backurl = DOMAIN.'/wmcms/notify/apilogin.php';
//引如登录自动加载sdk
$autoLoadFile = WMAPI.'login/'.$apiLoginType.'/autoload.php';
if( file_exists($autoLoadFile) )
{
	ClearApiLogin();
	//保存接口类型
	Session('api_login_type' , $apiLoginType);
	Session('api_bind' , $bind);
	require_once($autoLoadFile);
	//默认参数
	$data['appid'] = $appid;
	$data['apikey'] = $apikey;
	$data['secret'] = $secretkey;
	$data['backurl'] = $backurl;
	$data['auth'] = json_decode(Request('auth'),true);
	//new一个第三方登录类
	$loginSer = new OtherLogin($data);
	//获得跳转地址
	$loginUrl = $loginSer->GetJumpUrl();
	//如果不是跳转地址
	if( $loginUrl === false )
	{
		switch ($apiLoginType)
		{
			//微信小程序登录
			case "wxapplogin":
				$rs = $loginSer->GetSessionKey();
				break;
			//微信APP登录
			case "wxapp_login":
			//QQAPP登录
			case "qqapplogin":
				$rs = $loginSer->GetUserInfo();
				break;
		}
		//获取用户信息出现错误
		if( isset($rs['errmsg']) )
		{
			ReturnJson($rs['errmsg'],500);
		}
		//返回账号绑定或者生成方式
		$userConfig = GetModuleConfig('user');
		$rs['api'] = $apiLoginType;
		$rs['api_login_bind'] = $userConfig['api_login_bind'];
		$rs['apiuser'] = str::Encrypt(serialize($rs));
		//如果openid存在就查询是否绑定过了
		if( isset($rs['openid']) )
		{
			$userMod = NewModel('user.user');
			$userData = $userMod->GetUserByApi($apiLoginType , $rs['openid'], isset($rs['unionid'])?$rs['unionid']:'');
			$rs['user'] = ProcessReturnUser($userData);
		}
		ReturnJson('请求成功！',200,$rs);
	}
	else
	{
		header("Location:".$loginUrl);
	}
	die();
}
else
{
	tpl::ErrInfo($lang['user']['api_type_no']);
}
?>