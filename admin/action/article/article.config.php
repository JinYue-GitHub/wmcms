<?php
/**
* 文章配置文件保存处理器
*
* @version        $Id: article.config.php 2016年4月25日 14:33  weimeng
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
	SetOpLog( '修改文章模块设置' , 'article' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('article');
	
	Ajax();
}
?>