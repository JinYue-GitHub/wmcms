<?php
/**
* 后台模版加载总文件
*
* @version        $Id: templates.php 2016年4月2日 14:33  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$url = 'index.php?c='.Request('c');
//设置模版路径
$tempPath = 'templates/'.$pt.'/';


//是否加载控制器主文件前置方法。
if( file_exists('plugin/templates.before.php') )
{
	require 'plugin/templates.before.php';
}

//检查模版文件是否存在
if ( file_exists($tempPath.$cPath.'.php') )
{
	require $tempPath.$cPath.'.php';

	//是否加载控制器主文件后置方法。
	if( file_exists('plugin/templates.after.php') )
	{
		require 'plugin/templates.after.php';
	}
}
else
{
	die('后台视图文件：'.$tempPath.$cPath.'.php 不存在！');
}
?>