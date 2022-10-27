<?php
/**
* 邮件模版处理器
*
* @version        $Id: system.email.temp.php 2017年6月26日 16:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_temp_temp';
$emailMod = NewModel('system.email');

//修改配置信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		$wheresql['temp_id'] = $data['temp_id'];
		if( $emailMod->TempGetOne($wheresql) )
		{
			Ajax('对不起，该ID已经存在！' , 300);
		}
	
		//插入记录
		$emailMod->TempInsert($data);
		$where['temp_id'] = $data['temp_id'];
		//写入操作记录
		SetOpLog( '新增了邮件模版' , 'system' , 'insert' , $table , $where , $data );
		$info = '邮件模版新增成功!';
	}
	//如果是增加页面
	else
	{
		//修改数据
		$where['temp_id'] = $data['temp_id'];
		unset($data['temp_id']);
		$emailMod->TempUpdate($data,$where);
		//写入操作记录
		SetOpLog( '修改邮件模版' , 'system' , 'update' , $table  , $where , $data );
		$info = '邮件模版修改成功！';
	}

	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor','email', $where['temp_id']);
	
	Ajax($info);
}
//删除邮件模版
else if ( $type == 'del' )
{
	$where['temp_id'] = GetDelId();
	$emailMod->TempDel($where);
	//写入操作记录
	SetOpLog( '删除了邮件模版' , 'system' , 'delete' , $table , $where);
	Ajax('邮件模版删除成功!');
}
//使用禁用邮件模版
else if ( $type == 'status' )
{
	$data['temp_status'] = Request('status');
	$where['temp_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$emailMod->TempUpdate($data,$where);
	
	//写入操作记录
	SetOpLog( '邮件模版'.$msg , 'system' , 'update' , $table , $where);
	Ajax('邮件模版'.$msg);
}
?>