<?php
/**
* 用户请求处理器
*
* @version        $Id: index.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年12月19日 20:15  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

if( file_exists('user/'.$type.'.php') )
{
	//新建用户模型
	$userMod = NewModel('user.user');
	require_once $type.'.php';
}
else
{
	tpl::ErrInfo($lang['system']['action']['no_file']);
}
?>