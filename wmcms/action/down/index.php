<?php
/**
* 全系统下载功能参数验证处理器
*
* @version        $Id: index.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$uid = user::GetUid();
$downMod = NewModel('down.down');
if( file_exists('down/'.$type.'.php') )
{
	require_once $type.'.php';
}
else
{
	tpl::ErrInfo($lang['system']['action']['no_file']);
}
?>