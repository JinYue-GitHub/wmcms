<?php
/**
* 内容标签处理器
*
* @version        $Id: system.tags.php 2022年04月01日 11:50  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
list($module,$type) = explode('_', $type);
$table = '@system_tags';
$tagsMod = NewModel('system.tags');

//修改筛选信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$data['tags_module'] = $module;
	$where['tags_id'] = Request('tags_id');
	$addData = array('tags_author_rec'=>$data['tags_author_rec'],'tags_type_id'=>$data['tags_type_id']);
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
		$where['tags_id'] = $tagsMod->SetTags($module,$data['tags_name'],$addData);
		//写入操作记录
		SetOpLog( '新增了'.GetModuleName($module).'的内容标签'.$data['tags_name'] , 'system' , 'insert' , $table , $where , $data );
		Ajax(GetModuleName($module).'的内容标签新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改'.GetModuleName($module).'的内容标签' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$tagsMod->Update($data,$where,$module);
		Ajax(GetModuleName($module).'的内容标签修改成功！');
	}
}
//删除条件删选
else if( $type == 'del' )
{
	$where['tags_id'] = GetDelId();
	$tagsMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了'.GetModuleName($module).'的内容标签' , 'system' , 'delete' , $table , $where);
	Ajax(GetModuleName($module).'的内容标签删除成功!');
}
//清空登录记录
else if ( $type == 'clear' )
{
	$where['tags_module'] = $module;
	$tagsMod->Del($where);
	//写入操作记录
	SetOpLog( '清空了'.GetModuleName($module).'的内容标签' , 'system' , 'delete');
	Ajax(GetModuleName($module).'所有内容标签成功清空！');
}
//推荐或取消推荐
else if( $type == 'authorrec' )
{
	$data['tags_author_rec'] = Request('authorrec');
	$where['tags_id'] = GetDelId();

	if( Request('authorrec') == '1')
	{
	    $data['tags_author_rec'] = 1;
		$msg = '推荐成功';
	}
	else
	{
	    $data['tags_author_rec'] = 0;
		$msg = '取消成功';
	}
	$tagsMod->Update($data,$where);
	
	//写入操作记录
	SetOpLog( GetModuleName($module).'的内容标签'.$msg , 'system' , 'update' , $table , $where);
	Ajax(GetModuleName($module).'的内容标签'.$msg);
}
?>