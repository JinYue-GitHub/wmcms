<?php
/**
* 目录设置控制器文件
*
* @version        $Id: system.menu.menu.php 2016年3月31日 14:45  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$menuArr = $manager->GetMenu(false , 'a');
?>