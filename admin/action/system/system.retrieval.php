<?php
/**
* 筛选条件处理器
*
* @version        $Id: system.retrieval.php 2017年6月17日 11:26  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
list($module,$type) = explode('_', $type);
$table = '@system_retrieval';
$reMod = NewModel('system.retrieval');

//修改筛选信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$data['retrieval_module'] = $module;
	$where['retrieval_id'] = Request('retrieval_id');
	foreach ($data as $k=>$v)
	{
		if( $v == '' && $k != 'retrieval_value' )
		{
			Ajax(GetModuleName($module).'对不起，所有项都不能为空！' , 300);
		}
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['retrieval_id'] = $reMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了'.GetModuleName($module).'的筛选条件'.$data['retrieval_title'] , 'system' , 'insert' , $table , $where , $data );
		Ajax(GetModuleName($module).'的筛选条件新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改'.GetModuleName($module).'的筛选条件' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$reMod->Update($data,$where);
		Ajax(GetModuleName($module).'的筛选条件修改成功！');
	}
}
//删除条件删选
else if( $type == 'del' )
{
	$where['retrieval_id'] = GetDelId();
	$reMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了'.GetModuleName($module).'的筛选条件' , 'system' , 'delete' , $table , $where);
	Ajax(GetModuleName($module).'的筛选条件删除成功!');
}
//使用禁用条件
else if( $type == 'status' )
{
	$data['retrieval_status'] = Request('status');
	$where['retrieval_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$reMod->Update($data,$where);
	
	//写入操作记录
	SetOpLog( GetModuleName($module).'的筛选条件'.$msg , 'system' , 'update' , $table , $where);
	Ajax(GetModuleName($module).'的筛选条件'.$msg);
}

//修改检索分类信息
else if($type == 'type')
{
	if( $post['type'] )
	{
		foreach ($post['type'] as $k=>$v)
		{
			$reMod->TypeUpdate($v['data'],$v['id']);
		}
		//写入操作记录
		SetOpLog( '修改了'.GetModuleName($module).'检索分类！' , 'system' , 'update' , $table , $v['id'] , $v['data'] );
	}
	Ajax(GetModuleName($module).'恭喜您，检索分类修改成功!');
}
//使用禁用分类
else if( $type == 'typestatus' )
{
	$data['type_status'] = Request('status');
	$where['type_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$reMod->TypeUpdate($data,$where);
	
	//写入操作记录
	SetOpLog( GetModuleName($module).'的筛选分类'.$msg , 'system' , 'update' , $table , $where);
	Ajax(GetModuleName($module).'的筛选分类'.$msg);
}
?>