<?php
/**
* 绑定模块设置处理器
*
* @version        $Id: system.module.config.php 2016年9月14日 11:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//编辑绑定模块
if( $type == 'install' )
{
	$module = @array_values($_POST['module']);
	$moduleMod = NewModel('system.module');
	$moduleMod->Install($module);
	Ajax( '恭喜您，模块安装成功！' , 200);
}
?>