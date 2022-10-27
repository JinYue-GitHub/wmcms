<?php
/**
* 敏感词库配置处理器
*
* @version        $Id: system.safe.shield.php 2020年5月28日 10:59  weimeng
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
	$shield = $post['shield'];
	$disable = $post['disable'];
	unset($post['shield']);
	unset($post['disable']);
	
	//保存词库内容
	file::CreateFile(WMCONFIG.'key.shield.txt',$shield,1);
	file::CreateFile(WMCONFIG.'key.disable.txt',$disable,1);
	
	//保存开关配置
	$configMod->UpdateToForm($post);
	
	//写入操作记录
	SetOpLog( '修改敏感词库' , 'system' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('web');
	
	Ajax();
}
?>