<?php
/**
* 默认首页设置制器文件
*
* @version        $Id: system.menu.default_index.php 2017年8月11日 11:39  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$menuMod = NewModel('system.menu');
$data = $menuMod->DefaultGetOne(Session('admin_id'));
if(!$data)
{
	$defaultIndex='index_main';
}
else
{
	$defaultIndex = $data['default_controller'];
}
?>