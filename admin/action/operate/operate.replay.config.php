<?php
/**
* 评论配置保存处理器
*
* @version        $Id: operate.replay.config.php 2016年5月6日 23:09  weimeng
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
	SetOpLog( '修改评论模块设置' , 'replay' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('replay');
	
	Ajax();
}
?>