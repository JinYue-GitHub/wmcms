<?php
/**
* 公共方法文件
* @name 		     方法命名采用驼峰 plugin+插件名+方法名
* @version        $Id: function.php 2018年6月9日 10:23  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//插件公共方法
function PluginDemoTest()
{
	return '这是插件方法';
}

/**
 * 是否是手机号
 * @param unknown $phone
 * @return boolean
 */
function PluginDemoCheckMobile($phone)
{
	if( preg_match("/^1[34578]{1}\d{9}$/",$phone) )
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>