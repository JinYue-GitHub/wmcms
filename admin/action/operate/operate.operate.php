<?php
/**
* 互动处理器
*
* @version        $Id: operate.operate.php 2016年5月11日 9:45  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@operate_operate';


//删除请求记录
if ( $type == 'del' )
{
	$where['operate_id'] = GetDelId();
	
	wmsql::Delete($table, $where);
	SetOpLog( '删除了互动记录' , 'system' , 'delete' , $table , $where);
	
	Ajax('互动删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	$delType = Request('dt');
	//判断
	if( $delType == '' )
	{
		Ajax('对不起，请选择删除的互动类型!',300);
	}
	else
	{
		$where['operate_type'] = $delType;
	}
	wmsql::Delete($table , $where);

	//写入操作记录
	SetOpLog( '清空了互动记录' , 'system' , 'delete');
	Ajax('所有互动成功清空！');
}
?>