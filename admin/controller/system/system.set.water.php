<?php
/**
* 上传水印控制器文件
*
* @version        $Id: system.set.water.php 2016年3月28日 11:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'upload' );
$configCount = count($configArr);
?>