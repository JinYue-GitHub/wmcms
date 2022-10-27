<?php
/**
* zt页面处理器
*
* @version        $Id: operate.zt.php 2016年5月7日 21:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@zt_zt';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['zt'], 'e' );
	$where = $post['id'];
	
	if ( $data['zt_name'] == '' )
	{
		Ajax('对不起，专题标题不能为空！',300);
	}
	else if( !str::Number(GetKey($data,'type_id')) )
	{
		Ajax('对不起，专题分类必须选择！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$data['zt_time'] = time();
		$info = '恭喜您，专题添加成功！';
		$where['zt_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了专题'.$data['zt_name'] , 'zt' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，专题修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了专题'.$data['zt_name'] , 'zt' , 'update' , $table , $where , $data );
	}
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor','operate_zt' , $where['zt_id']);
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['zt_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了专题' , 'zt' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
		
	Ajax('专题删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了专题' , 'zt' , 'delete');
	Ajax('所有专题成功清空！');
}
//审核数据
else if ( $type == 'status' )
{
	$data['zt_status'] = Request('status');
	$where['zt_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '显示';
	}
	else
	{
		$msg = '隐藏';
	}
	//写入操作记录
	SetOpLog( $msg.'了专题' , 'zt' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('专题'.$msg.'成功!');
}
?>