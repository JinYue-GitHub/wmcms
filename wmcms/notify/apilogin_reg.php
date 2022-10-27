<?php
/**
* 未登录快捷登录注册操作
*
* @version        $Id: apilogin_reg.php 2022年03月27日 11:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//如果已经接入过或者是自动接入生成账号
if( $userConfig['api_login_bind'] == '0' || $loginData)
{
	//不存在api登录信息就注册一个用户。
	if( !$loginData )
	{
		$data['name'] = $apiLoginType.md5_16($userInfo['openid']);
		$data['nickname'] = $userInfo['nickname'];
		$data['psw'] = md5_16($apiLoginType.$userInfo['openid']);
		$data['type'] = $apiLoginType;
		$data['api'] = 1;
		$data['api_user'] = $userInfo;
		$loginData['api_uid'] = $userMod->Reg($data);
	}
	//清除api登录数据
	ClearApiLogin();
	$userData = $userMod->GetOne($loginData['api_uid']);
	if( $userData )
	{
		//是ajax请求
		if( $ajax )
		{
			$userData = ProcessReturnUser($userData);
			ReturnData( '' , $ajax , 200 ,$userData);
		}
		//不是ajax请求
		else
		{
			//写入登录属性，并且跳转到用户中心
			Cookie('user_account' , str::A($userData['user_name'], $userData['user_psw']) );
			header("Location:".tpl::url('user_home'));
		}
	}
	else
	{
		//删除当前用户的所有api登陆了信息
		$userMod->DelApiLogin($uid);
		ReturnData( $lang['user']['no'] , $ajax );
	}
}
else
{
	//获得页面的标题等信息
	C('page' ,  array(
		'pagetype'=>'user_apilogin' ,
		'dtemp'=>'user/apilogin.html',
		'label'=>'userlabel',
		'label_fun'=>'ApiLoginLabel',
		'user_info'=>$userInfo,
	));
	//设置seo信息
	tpl::GetSeo();
	
	//创建模版并且输出
	$tpl=new tpl();
	$tpl->display();
}
?>