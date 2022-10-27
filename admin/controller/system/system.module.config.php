<?php
/**
* 模块绑定功能控制器文件
*
* @version        $Id: system.module.config.php 2016年9月14日 10:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$moduleArr = $manager->GetModule($type);
?>