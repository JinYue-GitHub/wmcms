<?php
/**
* 短信模版处理器
*
* @version        $Id: system.sms.php 2021年03月17日 15:29  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$smsMod = NewModel('system.sms');
$table = '@system_sms';

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
		//插入记录
		$where['sms_id'] = $smsMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了短信模版' , 'system' , 'insert' , $table , $where , $data );
		$info = '短信模版新增成功!';
	}
	//如果是增加页面
	else
	{
		//修改数据
		$where['sms_id'] = Post('sms_id/i');
		unset($data['sms_id']);
		$smsMod->Update($data,$where);
		//写入操作记录
		SetOpLog( '修改短信模版' , 'system' , 'update' , $table  , $where , $data );
		$info = '短信模版修改成功！';
	}
	Ajax($info);
}
//删除短信模版
else if ( $type == 'del' )
{
	$where['sms_id'] = GetDelId();
	$smsMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了短信模版' , 'system' , 'delete' , $table , $where);
	Ajax('短信模版删除成功!');
}
//使用禁用短信模版
else if ( $type == 'status' )
{
	$data['sms_status'] = Request('status');
	$where['sms_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$smsMod->Update($data,$where);
	
	//写入操作记录
	SetOpLog( '短信模版'.$msg , 'system' , 'update' , $table , $where);
	Ajax('短信模版'.$msg);
}
?>