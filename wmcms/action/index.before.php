<?php
/**
* 二次开发处理器前置事件
*
* @version        $Id: index.before.php 2019年04月21日 10:44  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//是否存在前置文件
if( file_exists('plugin/'.$action.'/'.$type.'.before.php') )
{
	require_once 'plugin/'.$action.'/'.$type.'.before.php';
}
?>