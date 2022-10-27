<?php
/**
* 信息发送日志处理器
*
* @version        $Id: system.safe.msglog.php 2022年03月25日 16:40  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$logMod = NewModel('system.msglog');
$table = $logMod->table;
$where = array();

//删除日志
if ( $type == 'del' )
{
	$where['log_id'] = GetDelId();
	$logMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了信息发送记录' , 'system' , 'delete' , $table , $where);
	Ajax('信息发送记录删除成功!');
}
else if( $type == 'clear' )
{
	$logMod->Clear();
	//写入操作记录
	SetOpLog( '清空了信息发送记录' , 'system' , 'delete' , $table , $where);
	Ajax('信息发送记录清空成功!');
}
?>