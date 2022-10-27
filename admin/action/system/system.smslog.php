<?php
/**
* 短信发送日志处理器
*
* @version        $Id: system.smslog.php 2021年03月17日 15:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$logMod = NewModel('system.smslog');
$table = $logMod->table;
$where = array();

//删除日志
if ( $type == 'del' )
{
	$where['log_id'] = GetDelId();
	$logMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了短信发送记录' , 'system' , 'delete' , $table , $where);
	Ajax('短信发送记录删除成功!');
}
else if( $type == 'clear' )
{
	wmsql::Exec('TRUNCATE TABLE `@'.$table.'`');
	//写入操作记录
	SetOpLog( '清空了短信发送记录' , 'system' , 'delete' , $table , $where);
	Ajax('短信发送记录清空成功!');
}
?>