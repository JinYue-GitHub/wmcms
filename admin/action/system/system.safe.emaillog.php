<?php
/**
* 邮件日志处理器
*
* @version        $Id: system.safe.emaillog.php 2017年6月27日 16:19  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$emailMod = NewModel('system.email');
$table = '@system_email_log';

//删除登录记录
if ( $type == 'del' )
{
	$where['log_id'] = GetDelId();
	$emailMod->LogDel($where);

	//写入操作记录
	SetOpLog( '删除了邮件发送日志' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table, $where);
	Ajax('邮件发送日志删除成功!');
}
//清空登录记录
else if ( $type == 'clear' )
{
	wmsql::Exec('TRUNCATE TABLE `@'.$table.'`');
	//写入操作记录
	SetOpLog( '清空了邮件发送日志' , 'system' , 'delete');
	Ajax('所有邮件发送日志成功清空！');
}
?>