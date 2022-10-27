<?php
/**
* 道具分类处理器
*
* @version        $Id: props.type.php 2017年3月5日 17:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeMod = NewModel('props.type');

$table = '@props_type';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape($post['type'] , 'e');
	$where['type_id'] = $post['type_id'];
	
	if ( $data['type_name'] == '' )
	{
		Ajax('对不起，分类名字必须填写！',300);
	}
	else if( !str::Number($data['type_order']) )
	{
		Ajax('对不起，分类排序必须为数字！',300);
	}
	else if( !str::Number($data['type_topid']) )
	{
		Ajax('对不起，所属分类必须选择！',300);
	}
	else if( $typeMod->GetByName($data['type_module'] , $data['type_name'] , $where['type_id']) )
	{
		Ajax('对不起，当前分类已经存在！',300);
	}

	//查询上级所有id
	$data['type_pid'] = GetPids( $table , $data['type_topid'] );
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，道具分类添加成功！';
		$where['type_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了道具分类'.$data['type_name'] , $curModule , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，道具分类修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了道具分类'.$data['type_name'] , $curModule , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除分类
else if ( $type == 'del' )
{
	$where['type_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '删除了道具分类' , $curModule , 'delete' , $table , $where);
	//删除分类
	wmsql::Delete($table, $where);
	Ajax('道具分类删除成功!');
}
?>