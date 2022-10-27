<?php
/**
* 应用模块配置控制器文件
*
* @version        $Id: app.set.config.php 2016年5月16日 18:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'app' );
$configCount = count($configArr);
?>