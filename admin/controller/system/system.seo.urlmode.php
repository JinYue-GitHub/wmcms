<?php
/**
* url模式控制器
*
* @version        $Id: system.seo.urlmode.php 2018年12月22日 10:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$sysConfig = str::ArrRestKey($manager->GetConfig( 'system' ) , 'config_name');
$configArr = str::ArrRestKey($manager->GetConfig( 'urlmode' ) , 'config_name');
?>