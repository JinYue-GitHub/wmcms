<?php
/**
* 二次开发处理器后置事件
*
* @version        $Id: index.after.php 2019年04月21日 10:44  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//是否存在后置文件
if( file_exists('plugin/'.$actionPath.'/'.$actionFile.'.after.php') )
{
	require_once 'plugin/'.$actionPath.'/'.$actionFile.'.after.php';
}
?>