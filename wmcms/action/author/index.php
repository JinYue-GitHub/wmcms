<?php
/**
* 作者请求处理器
*
* @version        $Id: index.php 2016年12月19日 19:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$uid = user::GetUid();
//没有登录
str::EQ( $uid , 0 , $lang['user']['no_login'] );

if( file_exists('author/'.$type.'.php') )
{
	require_once $type.'.php';
}
else
{
	tpl::ErrInfo($lang['system']['action']['no_file']);
}
?>