<?php
/**
* 编辑模块配置文件
*
* @version        $Id: editor.common.php 2022年05月13日 16:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$BaseClass = array('file','str');
$BaseModule = array('user','novel','editor','search',);

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

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
//检测状态编辑个人状态
editor::CheckEditor();
?>