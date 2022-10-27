<?php
/**
* 论坛配置文件保存处理器
*
* @version        $Id: bbs.config.php 2016年5月18日 16:49  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$configMod = NewModel('system.config');

//如果请求信息存在
if( $post )
{
	$configMod->UpdateToForm($post);

	//写入操作记录
	SetOpLog( '修改论坛模块设置' , 'bbs' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('bbs');
	
	Ajax();
}
?>