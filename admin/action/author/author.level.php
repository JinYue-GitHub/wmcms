<?php
/**
* 作者等级处理器
*
* @version        $Id: author.level.php 2017年3月4日 14:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@author_level';

//新增数据
if ( $type == "add" )
{
	$data = str::Escape( GetKey($post,'level'), 'e' );
	if( !$data )
	{
		Ajax('对不起，请添加数据后再点击保存!',300);
	}
	else
	{
		$data['level_start'] = intval($data['level_start']);
		$data['level_end'] = intval($data['level_end']);
		$data['level_order'] = intval($data['level_order']);
		if( $data['level_name'] != '' )
		{
			$where['level_id'] = wmsql::Insert($table, $data);
			//写入操作记录
			SetOpLog( '新增了作者等级' , 'author' , 'insert' , $table , $where , $data );
			Ajax('恭喜您，作者等级添加成功！' , 200 );
		}
		else
		{
			Ajax('恭喜您，等级名不能为空！' , 300 );
		}
	}
}
//修改数据
else if ( $type == 'update')
{
	$where['level_id'] = intval(Get('id'));
	$data['level_name'] = Post('name');
	$data['level_start'] = intval(Post('start'));
	$data['level_end'] = intval(Post('end'));
	$data['level_order'] = intval(Post('order'));
	wmsql::Update($table, $data, $where);
	//写入操作记录
	SetOpLog( '修改了作者等级' , 'author' , 'delete' , $table , $where , $data);
	Ajax('作者等级修改成功!');
}
//修改全部数据
else if ( $type == 'upall')
{
	if( $post['level'] )
	{
		foreach ($post['level'] as $k=>$v)
		{
			wmsql::Update($table, $v['data'], $v['id']);
		}
	}
	//写入操作记录
	SetOpLog( '修改了全部作者等级' , 'author' , 'update' , $table);
	Ajax('全部作者等级修改成功!');
}
//删除数据
else if ( $type == 'del')
{
	$where['level_id'] = GetDelId();
	wmsql::Delete($table , $where);
	//写入操作记录
	SetOpLog( '删除了作者等级' , 'author' , 'delete' , $table , $where);
	Ajax('作者等级删除成功!');
}
?>