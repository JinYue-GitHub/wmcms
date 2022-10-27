<?php
/**
* 论坛模块配置控制器文件
*
* @version        $Id: bbs.set.config.php 2016年5月18日 15:23  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'bbs' );
$configCount = count($configArr);
?>