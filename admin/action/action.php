<?php
/**
* 处理器总文件
*
* @version        $Id: action.php 2016年4月2日 14:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//是否加载处理器主文件前置方法。
if( file_exists('plugin/action.before.php') )
{
	require 'plugin/action.before.php';
}

//检查控制器文件是否存在
if ( file_exists('action/'.$cPath.'.php') )
{
	//是否运行主处理器
	$runMainAction = true;
	//前置文件是否存在。
	if( file_exists('plugin/'.$cPath.'.action.php') )
	{
		require 'plugin/'.$cPath.'.action.php';
	}
	if( $runMainAction == true )
	{
		require 'action/'.$cPath.'.php';
	}

	//是否加载处理器主文件前置方法。
	if( file_exists('plugin/action.after.php') )
	{
		require 'plugin/action.after.php';
	}
	exit;
}
else
{
	die('后台处理器文件：action/'.$cPath.'.php 不存在！');
}
?>