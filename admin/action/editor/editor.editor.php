<?php
/**
* 编辑分组处理器
*
* @version        $Id: editor.editor.php 2022年05月13日 11:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$editorMod = NewModel('editor.editor');
$userMod = NewModel('user.user');
$table = $editorMod->table;

//修改编辑
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['editor_id'] = Request('editor_id');
	$uid = $data['editor_uid'];
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	//判断用户id是否存在
	if( !$userMod->GetOne($uid) )
	{
		Ajax('对不起，用户ID不存在！' , 300);
	}
	//判断用户是否已经添加了
	if( $editorMod->CheckExist($uid,$where['editor_id']) )
	{
		Ajax('对不起，当前用户id已存在！' , 300);
	}
	
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['editor_id'] = $editorMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了编辑'.$data['editor_name'] , 'editor' , 'insert' , $table , $where , $data );
		Ajax('编辑新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改编辑' , 'editor' , 'update' , $table  , $where , $data );
		//修改数据
		$editorMod->Update($data,$where);
		Ajax('编辑修改成功！');
	}
}
//快速切换状态
else if( $type == 'status' )
{
	$data['editor_status'] = Request('status');
	$where['editor_id'] = GetDelId();
	if( Request('status') == '1')
	{
		$msg = '启用';
	}
	else
	{
		$msg = '禁用';
	}
	//写入操作记录
	SetOpLog( $msg.'了编辑' , 'editor' , 'update' , $table , $where);
	$editorMod->Update( $data, $where);
	Ajax('编辑'.$msg.'成功!');
}
?>