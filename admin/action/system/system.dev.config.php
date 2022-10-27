<?php
/**
* 开发者配置处理器
*
* @version        $Id: system.dev.config.php 2017年6月4日 20:33  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$configMod = NewModel('system.config');
//如果请求信息存在
if( $type == 'config'  )
{
	$runlog = str::CheckElse(Request('runlog'), '1' , 'true' , 'false');
	$debug = str::CheckElse(Request('debug'), '1' , 'true' , 'false');
	$developer = str::CheckElse(Request('developer'), '1' , 'true' , 'false');
	$err = str::CheckElse(Request('err'), '1' , 'true' , 'false');
	$notfound = str::CheckElse(Request('notfound'), '1' , 'true' , 'false');
	$serverer = str::CheckElse(Request('serverer'), '1' , 'true' , 'false');
	$spider = str::CheckElse(Request('spider'), '1' , 'true' , 'false');
	//预警通知配置组
	$dev = Request('dev/a');
	$dev['warning_type'] = implode(',',$dev['warning_type']);
	
	//修改预警通知配置组
	$configMod->UpdateToForm(array('dev'=>$dev));
	//修改开发者配置
	$defineContent = file::GetFile(WMCONFIG.'define.config.php');
	$defineContent = preg_replace("/define\('RUNLOG',(.*?)\)/", "define('RUNLOG',{$runlog})", $defineContent);
	$defineContent = preg_replace("/define\('DEBUG',(.*?)\)/", "define('DEBUG',{$debug})", $defineContent);
	$defineContent = preg_replace("/define\('DEVELOPER',(.*?)\)/", "define('DEVELOPER',{$developer})", $defineContent);
	$defineContent = preg_replace("/define\('ERR',(.*?)\)/", "define('ERR',{$err})", $defineContent);
	$defineContent = preg_replace("/define\('NOTFOUND',(.*?)\)/", "define('NOTFOUND',{$notfound})", $defineContent);
	$defineContent = preg_replace("/define\('SERVERER',(.*?)\)/", "define('SERVERER',{$serverer})", $defineContent);
	$defineContent = preg_replace("/define\('SPIDER',(.*?)\)/", "define('SPIDER',{$spider})", $defineContent);
	file::CreateFile(WMCONFIG.'define.config.php', $defineContent , 1);
	
	//写入操作记录
	SetOpLog( '修改开发者配置' , 'system' , 'update' );
	Ajax();
}
?>