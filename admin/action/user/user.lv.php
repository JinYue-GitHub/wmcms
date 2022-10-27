<?php
/**
* 用户等级处理器
*
* @version        $Id: user.lv.php 2016年5月5日 22:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@user_level';

//修改分类信息
if ( $type == "add" || $type == "edit" )
{
	//新增数据
	if( $type == 'add' )
	{
		$data = str::Escape( GetKey($post,'level'), 'e' );
		if( !$data )
		{
			Ajax('对不起，请添加数据后再点击保存!',300);
		}
		foreach ($data as $k=>$v)
		{
			if( $v['level_name'] != '' )
			{
				$where['level_id'] = wmsql::Insert($table, $v);
				//写入操作记录
				SetOpLog( '新增了用户等级' , 'user' , 'insert' , $table , $where , $v );
			}
		}
		$info = '恭喜您，用户等级添加成功！';
	}
	else if( $type == 'edit' )
	{
		if( $post['level'] )
		{
			foreach ($post['level'] as $k=>$v)
			{
				wmsql::Update($table, $v['data'], $v['id']);
				//写入操作记录
				SetOpLog( '修改了用户等级' , 'user' , 'update' , $table , $v['id'] , $v['data'] );
			}
		}
		$info = '恭喜您，用户等级修改成功！';
	}
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del')
{
	$where['level_id'] = GetDelId();
	wmsql::Delete($table , $where);
	
	//写入操作记录
	SetOpLog( '删除了用户等级' , 'user' , 'delete' , $table , $where);
	
	Ajax('用户等级删除成功!');
}
?>