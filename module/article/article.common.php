<?php
/**
* 文章模块配置文件
*
* @version        $Id: article.common.php 2015年12月28日 21:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//基础clas和模块
$BaseClass = array('file','str');
$BaseModule = array('user','article','bbs','about','search');

//合并扩展模块
if ( !empty($ClassArr) ){
	$C['module']['inc']['class'] = array_merge( $BaseClass , $ClassArr );
}else{
	$C['module']['inc']['class'] = $BaseClass;
}
if ( !empty($ModuleArr) ){
	$C['module']['inc']['module'] = array_merge( $BaseModule , $ModuleArr );
}else{
	$C['module']['inc']['module'] = $BaseModule;
}

//引入公共文件
defined('route')?$dr='':$dr='../../';
require_once $dr.'wmcms/inc/common.inc.php';
?>