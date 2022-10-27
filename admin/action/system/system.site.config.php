<?php
/**
* 配置文件保存处理器
*
* @version        $Id: system.config.php 2016年3月25日 14:33  weimeng
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
	SetOpLog( '修改站群设置' , 'system' , 'update' );
	
	//更新系统配置文件
	$manager->UpConfig('web');
	//更新站群配置文件
	$siteSer = AdminNewClass('system.site');
	$siteSer->UpConfig();
	
	Ajax();
}
?>