<?php
/**
* 后台首页控制器文件
*
* @version        $Id: index.php 2016年3月24日 13:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
require 'class/manager.class.php';

$menuArr = $manager->GetMenu();
?>