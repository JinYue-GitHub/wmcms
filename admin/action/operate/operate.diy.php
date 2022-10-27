<?php
/**
* diy页面处理器
*
* @version        $Id: operate.diy.php 2016年5月7日 21:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@diy_diy';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['diy'], 'e' );
	$where = $post['id'];

	if( $data['diy_title'] == '' )
	{
		$data['diy_title'] = $data['diy_name'];
	}
	if( $data['diy_key'] == '' )
	{
		$data['diy_key'] = $data['diy_name'];
	}
	if( $data['diy_desc'] == '' )
	{
		$data['diy_desc'] = $data['diy_name'];
	}
	
	if ( $data['diy_name'] == '' || GetKey($data,'diy_content') == '' )
	{
		Ajax('对不起，单页标题和内容不能为空！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$data['diy_time'] = time();
		$info = '恭喜您，单页添加成功！';
		$where['diy_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了单页'.$data['diy_name'] , 'diy' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，单页修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了单页'.$data['diy_name'] , 'diy' , 'update' , $table , $where , $data );
	}
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor','operate_diy' , $where['diy_id']);
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['diy_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了单页' , 'diy' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
		
	Ajax('单页删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了单页' , 'diy' , 'delete');
	Ajax('所有单页成功清空！');
}
//审核数据
else if ( $type == 'status' )
{
	$data['diy_status'] = Request('status');
	$where['diy_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '显示';
	}
	else
	{
		$msg = '隐藏';
	}
	//写入操作记录
	SetOpLog( $msg.'了单页' , 'diy' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('单页'.$msg.'成功!');
}
?>