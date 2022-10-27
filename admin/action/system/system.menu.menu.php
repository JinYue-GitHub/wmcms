<?php
/**
* 菜单目录处理器
*
* @version        $Id: system.menu.php 2016年4月1日 10:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_menu';

//移动菜单显示顺序
if ( $type == 'order' )
{
	$post = str::Escape(Post() , 'e');

	if( $post['order'] < 0 )
	{
		$post['order'] = 0;
	}
	//检查参数
	if( !str::Number($post['id']) || !str::Number($post['order']) )
	{
		Ajax($post['order'].'对不起，菜单id和顺序必须为数字',300);
	}
	//设置条件
	$where['menu_id'] = $post['id'];
	$data['menu_order'] = $post['order'];
	//设置参数
	if( Post('pid') != '' && str::Number(Post('pid')) )
	{
		$data['menu_pid'] = $post['pid'];
	}
	
	
	//写入操作记录
	SetOpLog( '移动了菜单显示顺序' , 'system' , 'updata' , $table , $where , $data );
	
	
	WMSql::Update($table, $data, $where);

	Ajax('菜单移动成功!');
}
else if ( $type == 'edit' || $type == "add" )
{
	//判断父级id是否为空
	if ( $post['pid'] == '' )
	{
		$post['pid'] = 0;
	}
	//设置where条件
	$where['menu_id'] = $post['id'];
	//设置修改数据
	$data['menu_status'] = $post['status'];
	$data['menu_title'] = $post['title'];
	$data['menu_name'] = $post['name'];
	$data['menu_pid'] = $post['pid'];
	$data['menu_group'] = $post['group'];
	$data['menu_order'] = $post['order'];
	$data['menu_file'] = $post['file'];

	//新增菜单
	if( $type == "add" )
	{
		$where['menu_id'] = WMSql::Insert($table, $data);
		//写入操作记录
		SetOpLog( '新增了菜单' , 'system' , 'insert' , $table , $where , $data );
		Ajax('菜单新增成功!');
	}
	//修改菜单
	else
	{
		//写入操作记录
		SetOpLog( '修改了菜单' , 'system' , 'update' , $table , $where , $data );
		WMSql::Update($table, $data, $where);
		Ajax('菜单更新成功!');
	}
}
//删除菜单
else if ( $type == 'del' )
{
	$where['menu_id'] = Post('id');
	//写入操作记录
	SetOpLog( '删除了菜单' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table, $where);
	Ajax('菜单删除成功!');
}
//添加或者删除快捷菜单
else if ( $type == 'quick' )
{
	$table = '@system_menu_quick';
	$where['table'] = $table;
	$where['where']['quick_menu_id'] = Request('id');
	$where['where']['quick_manager_id'] = Session('admin_id');
	$data = wmsql::GetOne($where);
	//存在数据就删除
	if( $data )
	{
		$quickWhere = array("quick_id"=>$data['quick_id']);
		wmsql::Delete($table , $quickWhere);
		//写入操作记录
		SetOpLog( '取消了快捷菜单' , 'system' , 'delete' , $table , $quickWhere);
		Ajax('快捷菜单取消成功！');
	}
	//不存在就添加快捷菜单
	else
	{
		wmsql::Insert($table, $where['where']);
		//写入操作记录
		SetOpLog( '添加了快捷菜单' , 'system' , 'insert' , $table , $where['where']);
		Ajax('快捷菜单添加成功！');
	}
}
//快捷菜单排序
else if ( $type == 'quickorder' )
{
	if( empty($post['qucik']) )
	{
		Ajax('对不起，请添加快捷菜单后操作！',300);
	}
	else
	{
		foreach ($post['qucik'] as $k=>$v)
		{
			wmsql::Update('@system_menu_quick', array('quick_order'=>$v), array('quick_id'=>$k));
		}
		//写入操作记录
		SetOpLog( '更新了快捷菜单排序' , 'system' , 'update' , $table );
		Ajax('快捷菜单顺序更新成功！');
	}
}
//设置默认首页
else if ( $type == 'default_index' )
{
	$data['default_controller'] = Post('index');
	if( $data['default_controller'] == '' )
	{
		Ajax('对不起，控制器不能为空！');
	} 
	else
	{
		$menuMod = NewModel('system.menu');
		$defaultData = $menuMod->DefaultGetOne(Session('admin_id'));
		if( $defaultData )
		{
			$menuMod->DefaultSave($data,$defaultData['default_id']);
		}
		else
		{
			$data['default_mid'] = Session('admin_id');
			$menuMod->DefaultInsert($data);
		}
		//写入操作记录
		SetOpLog( '更新了新的默认首页' , 'system' , 'update' , $table );
		Ajax('新的默认首页更新成功，刷新后生效！');
	}
}
?>