<?php
/**
* 评论模块配置文件
*
* @version        $Id: replay.common.php 2018年08月19日 10:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//基础clas和模块
$BaseClass = array('file','str');
$BaseModule = array('all');

//合并扩展模块
if ( !empty($ClassArr) ){
	$C['module']['inc']['class'] = array_merge( $BaseClass , $ClassArr );
}else{
	$C['module']['inc']['class'] = $BaseClass;
}
if ( !empty($ModuleArr) ){
	$C['module']['inc']['module'] = array_merge( $ModuleArr ,$BaseModule );
}else{
	$C['module']['inc']['module'] = $BaseModule;
}

//引入公共文件
defined('route')?$dr='':$dr='../../';
require_once $dr.'wmcms/inc/common.inc.php';
?>