<?php
/**
* 作者模块配置文件
*
* @version        $Id: author.common.php 2016年12月18日 21:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月12日 10:15 weimeng
*
*/
//基础clas和模块
$BaseClass = array('file','str');
$BaseModule = array('user','novel','article','author','search');

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
$siteCache = false;
defined('route')?$dr='':$dr='../../';
require_once $dr.'wmcms/inc/common.inc.php';
//没有登录
if( isset($checkLogin) && $checkLogin == true )
{
	str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
}
?>