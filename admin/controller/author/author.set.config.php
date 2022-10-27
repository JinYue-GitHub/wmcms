<?php
/**
* 作者模块配置控制器文件
*
* @version        $Id: author.set.config.php 2016年12月19日 20:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$userConfig = GetModuleConfig('user');

$configArr = $manager->GetConfig( 'author' );
$configCount = count($configArr);
?>