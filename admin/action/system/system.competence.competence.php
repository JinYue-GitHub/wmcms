<?php
/**
* 权限处理器
*
* @version        $Id: system.competence.competence.php 2016年4月5日 13:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_competence';

if ( $type == 'edit' || $type == "add" )
{
	if ( $post['name'] == '' )
	{
		Ajax('对不起，必须权限名字不能为空！',300);
	}
	//设置where条件
	$where['comp_id'] = $post['id'];
	//设置修改数据
	$data['comp_name'] = $post['name'];
	$data['comp_content'] = $post['comp'];
	$data['comp_site'] = $post['site'];
	$data = str::Escape($data , 'e');

	if( $data['comp_site'] == '' )
	{
		Ajax('对不起，请至少选择一个站点!',300);
	}
	else if( $data['comp_content'] == '' )
	{
		Ajax('对不起，请至少选择一项权限!',300);
	}
	
	//新增菜单
	if( $type == "add" )
	{
		//插入记录
		$where['comp_id'] = WMSql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了权限'.$data['comp_name'] , 'system' , 'insert' , $table , $where , $data );
		
		Ajax('权限新增成功!');
	}
	//修改菜单
	else
	{
		//写入操作记录
		SetOpLog( '修改了权限'.$data['comp_name'] , 'system' , 'update' , $table , $where , $data );
		
		WMSql::Update($table, $data, $where);
		Ajax('权限更新成功!');
	}
}
else if ( $type == 'del' )
{
	$where['comp_id'] = $post['id'];
	
	//写入操作记录
	SetOpLog( '删除了权限' , 'system' , 'delete' , $table , $where);

	wmsql::Delete($table, $where);
	
	Ajax('权限删除成功!');
}
?>