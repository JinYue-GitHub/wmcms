<?php
/**
* 管理员操作记录处理器
*
* @version        $Id: system.safe.operation.php 2016年4月10日 14:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@manager_operation';

//删除请求记录
if ( $type == 'del' )
{
	$where['operation_id'] = GetDelId();
	
	wmsql::Delete($table, $where);
	Ajax('操作记录删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Exec('TRUNCATE TABLE `@'.$table.'`');
	//写入操作记录
	SetOpLog( '清空了操作记录' , 'system' , 'delete');
	Ajax('所有操作记录成功清空！');
}
?>