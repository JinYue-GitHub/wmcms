<?php
/**
* 插件入口文件
*
* @version        $Id: plugin.php 2018年6月4日 19:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//载入类文件
$C['module']['inc']['class']=array('file','str');
//设置使用模块功能
$C['module']['inc']['module']=array('all');

//引入公共文件
require_once 'wmcms/inc/common.inc.php';
//引入插件首页
require_once 'plugin/index.php';
?>