<?php
/**
* 留言 处理器
*
* @version        $Id: operate.message.php 2016年5月7日 17:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@message_message';


//删除数据
if ( $type == 'del' )
{
	$where['message_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了留言' , 'message' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
		
	Ajax('留言删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了留言' , 'message' , 'delete');
	Ajax('所有留言成功清空！');
}
//审核数据
else if ( $type == 'status' )
{
	$data['message_status'] = Request('status');
	$where['message_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '通过审核';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了留言' , 'message' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('留言'.$msg.'成功!');
}
?>