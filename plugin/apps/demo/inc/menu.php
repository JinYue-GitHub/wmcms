<?php
/**
* 插件的后台管理目录文件
* @version        $Id: menu.php 2018年6月12日 21:53  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$pluginMenu = array(
	'system'=>array(
		'name'=>'系统菜单',
		'menu'=>array(
			array('name'=>'参数配置','action'=>'config'),
		),
	),
	'apply'=>array(
		'name'=>'业务菜单',
		'menu'=>array(
			array('name'=>'报名列表','action'=>'apply_list'),
		),
	),
);
?>