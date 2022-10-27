<?php
/**
* 图集模块配置控制器文件
*
* @version        $Id: picture.set.config.php 2016年5月15日 16:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'picture' );
$configCount = count($configArr);
?>