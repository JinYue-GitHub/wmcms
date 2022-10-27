<?php
/**
* 应用配置文件保存处理器
*
* @version        $Id: app.config.php 2016年5月17日 16:30  weimeng
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
	SetOpLog( '修改应用模块设置' , 'app' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('app');
	
	Ajax();
}
?>