<?php
/**
* 快捷菜单控制器文件
*
* @version        $Id: system.menu.quick.php 2016年5月15日 22:39  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//获得快捷菜单
$quickMenuArr = $manager->GetQuickMenu();
?>