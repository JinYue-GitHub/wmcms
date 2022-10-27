<?php
/**
* 小说配置文件保存处理器
*
* @version        $Id: novel.config.php 2016年5月4日 14:45  weimeng
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
	SetOpLog( '修改小说模块设置' , 'novel' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('novel');
	
	Ajax();
}
?>