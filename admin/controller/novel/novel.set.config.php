<?php
/**
* 小说模块配置控制器文件
*
* @version        $Id: novel.set.config.php 2016年4月30日 10:32  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'novel' );
$configCount = count($configArr);

$userConfig = GetModuleConfig('user');
?>