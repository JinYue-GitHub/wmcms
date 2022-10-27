<?php
/**
* 广告分类处理器
*
* @version        $Id: operate.ad.type.php 2016年5月8日 14:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@ad_type';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['type'], 'e' );
	$where = $post['id'];
	
	if ( $data['type_name'] == '')
	{
		Ajax('对不起，广告分类名字必须填写！',300);
	}

	//分类名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['type_name'] = $data['type_name'];
	$wheresql['where']['type_id'] = array('<>',$where['type_id']);
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该分类已经存在！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，广告分类添加成功！';
		$where['type_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了广告分类'.$data['type_name'] , 'system' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，广告分类修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了广告分类'.$data['type_name'] , 'system' , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['type_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了广告分类' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);

	Ajax('广告删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了广告分类' , 'system' , 'delete' , $table);
	Ajax('所有广告分类成功清空！');
}
?>