<?php
/**
* 专题模块配置控制器文件
*
* @version        $Id: operate.zt.config.php 2018年8月15日 19:44  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'zt' );
$configCount = count($configArr);
?>