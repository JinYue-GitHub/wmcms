<?php
/**
* 后台控制器总文件
*
* @version        $Id: controller.php 2016年4月2日 14:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//是否加载控制器主文件前置方法。
if( file_exists('plugin/controller.before.php') )
{
	require 'plugin/controller.before.php';
}

//检查控制器文件是否存在
if ( file_exists('controller/'.$cPath.'.php') )
{
	//是否运行主控制器
	$runMainControl = true;
	//前置文件是否存在。
	if( file_exists('plugin/'.$cPath.'.before.php') )
	{
		//可以在前置控制器设置不运行主控制器。
		require 'plugin/'.$cPath.'.before.php';
	}
	if( $runMainControl == true )
	{
		require 'controller/'.$cPath.'.php';
	}
	//后置文件是否存在
	if( file_exists('plugin/'.$cPath.'.after.php') )
	{
		require 'plugin/'.$cPath.'.after.php';
	}

	//是否加载控制器主文件前置方法。
	if( file_exists('plugin/controller.after.php') )
	{
		require 'plugin/controller.after.php';
	}
}
else if(DEBUG && ERR)
{
	die('后台控制器文件：controller/'.$cPath.'.php 不存在！');
}
?>