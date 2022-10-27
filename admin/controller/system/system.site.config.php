<?php
/**
* 站群配置控制器文件
*
* @version        $Id: system.seo.site_config.php 2017年5月26日 18:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'site' );
$configCount = count($configArr);
$siteType = GetKey($configArr,'0,config_value');
?>