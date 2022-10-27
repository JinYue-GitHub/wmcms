<?php
/**
* 作品关联处理器
*
* @version        $Id: editor.wordks.php 2022年05月13日 14:39  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$bindMod = NewModel('editor.bind');
$worksMod = NewModel('editor.works');
$table = $worksMod->table;

//修改关联
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['works_id'] = Request('works_id');
	$bindId = $data['works_bind_id'];
	$cid = $data['works_cid'];
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	//获取分组绑定的数据
	$bindData = $bindMod->GetById($bindId);
	if( !$bindData )
	{
		Ajax('对不起，分组绑定id不存在！' , 300);
	}
	$data['works_module'] = $bindData['bind_module'];
	$data['works_editor_id'] = $bindData['bind_editor_id'];
	$data['works_group_id'] = $bindData['bind_group_id'];
	//判断内容id是否存在
	if( $tableSer->GetCount($data['works_module'],$cid)=='0' )
	{
		Ajax('对不起，内容ID不存在！' , 300);
	}
	//检查是否存在同样的数据了
	if( $worksMod->CheckExist($data,$where['works_id']) )
	{
		Ajax('对不起，已存在重复的数据！' , 300);
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['works_id'] = $worksMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了作品关联' , 'editor' , 'insert' , $table , $where , $data );
		Ajax('作品关联新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改作品关联' , 'editor' , 'update' , $table  , $where , $data );
		//修改数据
		$worksMod->Update($data,$where);
		Ajax('作品关联修改成功！');
	}
}
//删除关联
else if( $type == 'del' )
{
	$where['works_id'] = GetDelId();
	$worksMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了作品关联' , 'editor' , 'delete' , $table , $where);
	Ajax('删除了分组作品关联!');
}
?>