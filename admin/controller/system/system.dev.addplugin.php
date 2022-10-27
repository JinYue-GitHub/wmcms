<?php
/**
* 新增API接口配置工具
*
* @version        $Id: system.dev.addapi.php 2018年09月10日 20:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//插件根目录
$pluginPath = WMPLUGIN.'apps/';
//查询所有插件
$pluginMod = NewModel('plugin.plugin');
$data = $pluginMod->GetList();
if( !$data )
{
	$data = array();
}
?>