<?php
/**
* 内容标签分类处理器
*
* @version        $Id: system.tags.type.php 2022年04月12日 17:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
list($module,$type) = explode('_', $type);
$tagsTypeMod = NewModel('system.tagstype');
$table = $tagsTypeMod->table;

//修改筛选信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$data['type_module'] = $module;
	$data['type_order'] = str::Int($data['type_order']);
	$where['type_id'] = Request('type_id');
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax(GetModuleName($module).'对不起，所有项都不能为空！' , 300);
		}
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
	    
		//插入记录
		$where['type_id'] = $tagsTypeMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了'.GetModuleName($module).'的内容标分类签' , 'system' , 'insert' , $table , $where , $data );
		Ajax(GetModuleName($module).'的内容标签分类新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改'.GetModuleName($module).'的内容标签分类' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$tagsTypeMod->Update($data,$where);
		Ajax(GetModuleName($module).'的内容标签分类修改成功！');
	}
}
//删除条件删选
else if( $type == 'del' )
{
	$where['type_id'] = GetDelId();
	$tagsTypeMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了'.GetModuleName($module).'的内容标签分类' , 'system' , 'delete' , $table , $where);
	Ajax(GetModuleName($module).'的内容标签分类删除成功!');
}
?>