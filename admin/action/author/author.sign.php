<?php
/**
* 作者等级处理器
*
* @version        $Id: author.sign.php 2017年3月4日 14:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@author_sign';

//新增签约等级
if ( $type == "add" )
{
	$data = str::Escape( GetKey($post,'sign'), 'e' );
	if( !$data )
	{
		Ajax('对不起，请添加数据后再点击保存!',300);
	}
	else
	{
		list($w,$u) = explode(":", $data['sign_divide']);
		if( $w+$u != 10 )
		{
			Ajax('对不起，分成比例加起来只能等于10!' , 300 );
		}
		if( $data['sign_name'] != '' )
		{
			$where['sign_id'] = wmsql::Insert($table, $data);
			//写入操作记录
			SetOpLog( '新增了签约等级' , 'author' , 'insert' , $table , $where , $data );
			Ajax('恭喜您，作者签约等级添加成功！' , 200 );
		}
		else
		{
			Ajax('恭喜您，签约等级名不能为空！' , 300 );
		}
	}
}
//修改签约数据
else if ( $type == 'update')
{
	$where['sign_id'] = intval(Get('id'));
	$data['sign_name'] = Post('name');
	$data['sign_divide'] = Post('divide');
	$data['sign_gold1'] = Post('gold1');
	$data['sign_gold2'] = Post('gold2');
	$data['sign_order'] = Post('order');
	list($w,$u) = explode(":", $data['sign_divide']);
	if( $w+$u != 10 )
	{
		Ajax('对不起，分成比例加起来只能等于10!' , 300 );
	}
	wmsql::Update($table, $data, $where);
	//写入操作记录
	SetOpLog( '修改了作者签约等级' , 'author' , 'update' , $table , $where , $data);
	Ajax('作者签约等级修改成功!');
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
	SetOpLog( '修改了全部签约等级' , 'author' , 'update' , $table);
	Ajax('全部签约等级修改成功!');
}
//删除数据
else if ( $type == 'del')
{
	$where['sign_id'] = GetDelId();
	wmsql::Delete($table , $where);
	//写入操作记录
	SetOpLog( '删除了作者签约等级' , 'author' , 'delete' , $table , $where);
	Ajax('作者签约等级删除成功!');
}
?>