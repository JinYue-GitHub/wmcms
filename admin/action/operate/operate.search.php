<?php
/**
* 搜索处理器
*
* @version        $Id: operate.search.php 2016年5月7日 11:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@search_search';

//删除记录
if ( $type == 'rec' )
{
	$data['search_rec'] = Request('rec');
	$where['search_id'] = GetDelId();

	if( Request('rec') == '1')
	{
		$msg = '设置推荐';
	}
	else
	{
		$msg = '取消推荐';
	}
	
	wmsql::Update($table, $data, $where);
	//写入操作记录
	SetOpLog( $msg.'了搜索关键词' , 'system' , 'update' , $table , $where);
	Ajax('搜索关键词'.$msg.'成功!');
}
//删除记录
else if ( $type == 'del' )
{
	$where['search_id'] = GetDelId();
	
	wmsql::Delete($table, $where);
	SetOpLog( '删除了搜索记录' , 'system' , 'delete' , $table , $where);
	
	Ajax('搜索记录删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了搜索记录' , 'system' , 'delete');
	Ajax('所有搜索记录成功清空！');
}
?>