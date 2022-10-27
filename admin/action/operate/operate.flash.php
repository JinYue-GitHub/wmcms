<?php
/**
* 幻灯片处理器
*
* @version        $Id: operate.flash.php 2016年5月7日 14:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@flash_flash';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['flash'], 'e' );
	$where = $post['id'];
	
	if ( $data['flash_title'] == '' || $data['flash_url'] == '' )
	{
		Ajax('对不起，幻灯片标题和url必须填写！',300);
	}
	else if( !str::Number(GetKey($data,'type_id')) )
	{
		Ajax('对不起，幻灯片分类必须选择！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$data['flash_time'] = time();
		$info = '恭喜您，幻灯片添加成功！';
		$where['flash_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了幻灯片'.$data['flash_title'] , 'system' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，幻灯片修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了幻灯片'.$data['flash_title'] , 'system' , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['flash_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了幻灯片' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
		
	Ajax('幻灯片删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了幻灯片' , 'system' , 'delete' , $table);
	Ajax('所有幻灯片成功清空！');
}
//审核数据
else if ( $type == 'status' )
{
	$data['flash_status'] = Request('status');
	$where['flash_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '显示';
	}
	else
	{
		$msg = '隐藏';
	}
	//写入操作记录
	SetOpLog( $msg.'了幻灯片' , 'system' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('幻灯片'.$msg.'成功!');
}
?>