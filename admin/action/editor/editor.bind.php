<?php
/**
* 编辑分组成员编辑处理器
*
* @version        $Id: editor.bind.php 2022年05月13日 11:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$groupMod = NewModel('editor.group');
$editorMod = NewModel('editor.editor');
$bindMod = NewModel('editor.bind');
$table = $editorMod->table;

//修改编辑
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['bind_id'] = Request('bind_id');
	$groupId = $data['bind_group_id'];
	$editorIid = $data['bind_editor_id'];
	//设置编辑分组
	$groupData = $groupMod->GetById($groupId);
	$data['bind_module'] = $groupData['group_module'];
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	//判断用户是否已经添加了
	if( $bindMod->CheckExist($groupId,$editorIid,$where['bind_id']) )
	{
		Ajax('对不起，当前分组已经存在该编辑！' , 300);
	}
	
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['bind_id'] = $bindMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了分组成员' , 'editor' , 'insert' , $table , $where , $data );
		Ajax('分组成员新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改分组成员' , 'editor' , 'update' , $table  , $where , $data );
		//修改数据
		$bindMod->Update($data,$where);
		Ajax('分组成员修改成功！');
	}
}
//删除关联
else if( $type == 'del' )
{
	$where['bind_id'] = GetDelId();
	$bindMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了分组成员' , 'editor' , 'delete' , $table , $where);
	Ajax('删除了分组分组成员!');
}
?>