<?php
/**
* 验证码配置处理器
*
* @version        $Id: system.safe.code.php 2017年5月13日 14:33  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$configMod = NewModel('system.config');

//如果请求信息存在
if( $type == 'config'  )
{
	$configMod = NewModel('system.config');
	$configMod->UpdateToForm($post);
	
	//写入操作记录
	SetOpLog( '修改验证码设置' , 'system' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('web');
	
	Ajax();
}
?>