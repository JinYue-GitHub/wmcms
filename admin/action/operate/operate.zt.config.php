<?php
/**
* 专题配置文件保存处理器
*
* @version        $Id: zt.config.php 2018年8月15日 20:46  weimeng
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
	SetOpLog( '修改专题模块设置' , 'zt' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('zt');
	
	Ajax();
}
?>