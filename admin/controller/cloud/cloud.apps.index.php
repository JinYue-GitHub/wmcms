<?php
/**
* 插件管理控制器
*
* @version        $Id: cloud.apps.plugin.manager.php 2018年6月12日 21:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
define('WMSHOP',HTTP_TYPE.'://shop.'.WMDOMAIN);
//所有应用数组
$appArr = array();

$appid = $C['config']['api']['system']['api_appid'];
$apikey = $C['config']['api']['system']['api_apikey'];
$secret = $C['config']['api']['system']['api_secretkey'];
$homeUrl = WMSHOP.'/index.php?c=shop&m=shop&a=index&domain='.DOMAIN.'&appid='.$appid.'&apikey='.$apikey.'&secret='.$secret;

//排除的文件夹
$arr = array('system','ajax','site');
$path = '../templates/';
$templateArr = file::FloderList( $path , $arr);
$installTempArr = $tempArr = array();
//查询所有模版
if( $templateArr )
{
	foreach ($templateArr as $k=>$v)
	{
		$appArr[$v['file']] = GetTempCopy( $v['file'] , $path);
	}
}

//查询所有插件
$path = '../plugin/apps/';
$pluginArr = file::FloderList( '../plugin/apps/' );
//查询所有模版
if( $pluginArr )
{
	foreach ($pluginArr as $k=>$v)
	{
		$appArr[$v['file']] = GetTempCopy( $v['file'] , $path);
	}
}
?>