<?php
/**
* 登录记录处理器
*
* @version        $Id: system.safe.log.php 2016年4月6日 16:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@manager_login';

//删除登录记录
if ( $type == 'del' )
{
	$where['login_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '删除了登录记录' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table, $where);
	Ajax('登录记录删除成功!');
}
//清空登录记录
else if ( $type == 'clear' )
{
	wmsql::Exec('TRUNCATE TABLE `@'.$table.'`');
	//写入操作记录
	SetOpLog( '清空了登录记录' , 'system' , 'delete');
	Ajax('所有登录记录成功清空！');
}
?>