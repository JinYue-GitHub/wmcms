<?php
/**
* 编辑分组处理器
*
* @version        $Id: editor.group.php 2022年05月13日 10:26  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$groupMod = NewModel('editor.group');
$table = $groupMod->table;

//修改分组
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['group_id'] = Request('group_id');
	$data['group_order'] = str::Int($data['group_order']);
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['group_id'] = $groupMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了编辑分组'.$data['group_name'] , 'editor' , 'insert' , $table , $where , $data );
		Ajax('编辑分组新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改编辑分组' , 'editor' , 'update' , $table  , $where , $data );
		//修改数据
		$groupMod->Update($data,$where);
		Ajax('编辑分组修改成功！');
	}
}
//删除
else if( $type == 'del' )
{
	$where['group_id'] = GetDelId();
	$groupMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了编辑分组' , 'editor' , 'delete' , $table , $where);
	Ajax('编辑分组删除成功!');
}
?>