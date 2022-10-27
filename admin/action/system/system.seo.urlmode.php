<?php
/**
* url模式保存处理器
*
* @version        $Id: system.seo.urlmode.php 2018年12月22日 11:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$configMod = NewModel('system.config');

//如果请求信息存在
if( $post && $type == 'config' )
{
	//如果是动态或者伪静态地址则设置文件名字
	if( $post['urlmode']['url_type'] <= '2')
	{
		$file = 'old';
	}
	//
	else
	{
		$file = 'new';
	}
	//开始复制首页
	file::CopyFile(WMMODULE, WMROOT,'index_'.$file.'.php','index.php');
	//开始伪静态规则
	require_once WMCONFIG.'/htaccess.config.php';
	file_put_contents(WMROOT.'.htaccess', $$file);
	
	//修改配置
	$configMod->UpdateToForm($post);
	//写入操作记录
	SetOpLog( '修改URL模式设置' , 'system' , 'update' );
	//更新配置文件
	$manager->UpConfig('web');
	//更新路由配置文件
	$manager->UpConfig('route');
	Ajax();
}
?>