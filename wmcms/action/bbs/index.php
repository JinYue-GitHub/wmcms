<?php
/**
* 全系统论坛处理验证处理器
*
* @version        $Id: index.php 2016年5月29日 12:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$module = 'bbs';
$bbsConfig = GetModuleConfig('bbs');
$bbsMod = NewModel('bbs.bbs');
$typeMod = NewModel('bbs.type');
$uploadMod = NewModel('upload.upload');

//获得用户id
$uid = user::GetUid();
//判断用户是否登录了
str::EQ( $uid , 0 , $lang['bbs']['login_no'] , $ajax );

if( !file_exists('bbs/'.$type.'.php') )
{
	tpl::ErrInfo($lang['system']['action']['no_file']);
}
else
{
	require_once $type.'.php';
}
?>