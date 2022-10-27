<?php
/**
* API登录返回请求处理
*
* @version        $Id: apilogin.php 2017年4月8日 12:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once '../../module/user/user.common.php';

//是否是ajax请求
$ajax = str::IsTrue( Request('ajax') , 'yes' , 'page.ajax');
//返回操作类型
$bind = Session('api_bind');
//获取使用登录接口类型
$apiLoginType = Session('api_login_type');
//如果session不存在就读取网页请求的api值
if( $apiLoginType == '' )
{
	$apiLoginType = Request('api');
}

//绑定账号必须是登录状态
if( $bind == '1' )
{
    str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
}
//快捷登录不允许登录状态
else
{
    str::RT( user::GetUid() , 0 , $lang['user']['islogin'] );
}

//接口参数设置
$userInfo['openid'] = '';
$appid = C('config.api.'.$apiLoginType.'.api_appid');
$apikey = C('config.api.'.$apiLoginType.'.api_apikey');
$secretkey = C('config.api.'.$apiLoginType.'.api_secretkey');
$open = C('config.api.'.$apiLoginType.'.api_open');
//定义回调地址
$backurl = DOMAIN.'/wmcms/notify/apilogin.php';
//自动加载登录sdk文件路径
$autoLoadFile = WMAPI.'login/'.$apiLoginType.'/autoload.php';

if( $open != '1' )
{
	tpl::ErrInfo($lang['user']['api_close']);
}
//接口文件不存在
else if( !file_exists($autoLoadFile) )
{
	tpl::ErrInfo($lang['user']['api_type_no']);
}
//不存在用户信息
else if( Session('api_login_userinfo') == '' )
{
	Session('apilogin_type' , $apiLoginType);
	//引如登录自动加载sdk
	require_once(WMAPI.'login/'.$apiLoginType.'/autoload.php');

	//默认参数
	$apiConfig['appid'] = $appid;
	$apiConfig['apikey'] = $apikey;
	$apiConfig['secret'] = $secretkey;
	$apiConfig['backurl'] = $backurl;
	$apiConfig['auth'] = json_decode(Request('auth'),true);
	$loginSer = new OtherLogin($apiConfig);
	$userInfo = $loginSer->GetUserInfo();
	$userInfo['api'] = $apiLoginType;
	//接口返回错误
	if( isset($userInfo['errmsg']) )
	{
		ReturnData( $userInfo['errmsg'] , $ajax );
	}
	//信息不存在或者为空
	else if( !isset($userInfo['openid']) || !isset($userInfo['nickname']) 
		|| $userInfo['openid'] == '' || $userInfo['nickname'] == '' )
	{
		ReturnData( $lang['user']['api_data_err'] , $ajax );
	}
}
//存在保存的用户数据
else if( Session('api_login_userinfo') <> '' )
{
	$userInfo = unserialize(str::Encrypt(Session('api_login_userinfo'),'D'));
}
$userInfo['api'] = $apiLoginType;
Session('api_login_userinfo',str::Encrypt(serialize($userInfo)));

//处理数据
if( !empty($userInfo['openid']) )
{
	$userConfig = GetModuleConfig('user');
	//查询唯一OpenID是否存在本站数据库。
	$userMod = NewModel('user.user');
	$loginData = $userMod->GetApiLogin($apiLoginType , $userInfo['openid'], '',isset($userInfo['unionid'])?$userInfo['unionid']:'');
	if( $bind == '1' )
	{
        require_once 'apilogin_bind.php';
	}
	else
	{
        require_once 'apilogin_reg.php';
	}
}
else
{
	//清除api登录数据
	ClearApiLogin();
	ReturnData( $lang['user']['api_openid_err'] , $ajax );
}
die();
?>