<?php
/**
* 统计代码保存处理器
*
* @version        $Id: operate.tongji.php 2016年5月7日 22:33  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$configMod = NewModel('system.config');

//如果请求信息存在
if ( $type == 'edit' )
{
	$configMod->UpdateById(42,$post['tongji']['tongji']);

	//写入操作记录
	SetOpLog( '修改统计代码' , 'system' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('web');
	
	Ajax();
}
?>