<?php
/**
* 重置密码操作处理
*
* @version        $Id: repsw.php 2016年5月29日 9:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$psw = str::IsEmpty( Post('psw') , $lang['user']['psw_no']);
$repsw = str::IsEmpty( Post('repsw') , $lang['user']['repsw_no']);
$key = str::IsEmpty( Post('key') , $lang['user']['key_no']);
//获取key的超时
$userName = $userMod->CheckGetPswKey($key);
if( $userName === false )
{
	ReturnData( $lang['user']['key_exp'] );
}

//密码长度和密码格式
str::CheckLen( $psw , '6,16' , $lang['user']['psw_len']  );
str::NCN( $psw, $lang['user']['psw_err'] );
//两次密码是否相等
str::NEQ( $psw, $repsw , $lang['user']['psw_repsw'] );

//查询账号
$where['user_name'] = $userName;
$data = $userMod->GetOne($where);
//存在数据就修改
if( $data )
{
	//设置密码并且修改密码
	$salt = str::GetSalt();
	$userMod->psw = str::E($psw,$salt);
	$userMod->salt = $salt;
	$result = $userMod->SavePsw($data['user_id']);
	//清空重置密码key
	$userMod->DelGetPswKey($key);
	//密码重置成功
	if( $result )
	{
		$info = GetInfo($lang['user']['operate']['repsw'] , 'user_login');
		ReturnData( $info , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['user']['operate']['repsw']['fail'] , $ajax);
	}
}
else
{
	ReturnData( $lang['user']['repsw_err'] );
}
?>