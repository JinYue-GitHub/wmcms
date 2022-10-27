<?php
/**
* 管理员请求记录处理器
*
* @version        $Id: system.safe.request.php 2016年4月9日 16:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@manager_request';

//删除请求记录
if ( $type == 'del' )
{
	$where['request_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '删除了请求记录' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table, $where);
	Ajax('请求记录删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Exec('TRUNCATE TABLE `@'.$table.'`');
	//写入操作记录
	SetOpLog( '清空了请求记录记录' , 'system' , 'delete');
	Ajax('所有请求记录成功清空！');
}
?>