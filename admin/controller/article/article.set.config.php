<?php
/**
* 文章模块配置控制器文件
*
* @version        $Id: article.set.config.php 2016年4月22日 16:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

$configArr = $manager->GetConfig( 'article' );
$configCount = count($configArr);
?>