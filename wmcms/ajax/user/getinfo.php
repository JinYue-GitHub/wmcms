<?php
/**
* 获得用户的信息
*
* @version        $Id: getinfo.php 2016年11月17日 22:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//没有登录
if( user::GetUid() == 0 )
{
	$code = 300;
	$data['new_msg'] = 0 ;
	$msg = $lang['user']['no_login'];
}
else
{
	$code = 200;
	$data = user::GetInfo();
	//新消息条数
	$msgData = user::GetMsg();
	$data['new_msg'] = $msgData['new_msg'];

	unset($data['user_psw']);
	unset($data['user_salt']);
}

ReturnData(null , $ajax , $code , $data);
?>