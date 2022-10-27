<?php
/**
* 邮件配置处理器
*
* @version        $Id: system.email.email.php 2017年6月26日 16:19  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_email';
$emailMod = NewModel('system.email');

//修改配置信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['email_id'] = Request('email_id');
	
	//密码加密判断处理
	if ( str_replace('*','',$data['email_psw']) == '' )
	{
		unset($data['email_psw']);
	}
	else
	{
		$data['email_psw'] = str::Encrypt( $data['email_psw'] , 'E', C('config.api.system.api_apikey') );
	}
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['email_id'] = $emailMod->EmailInsert($data);
		//写入操作记录
		SetOpLog( '新增了邮件服务' , 'system' , 'insert' , $table , $where , $data );
		Ajax('邮件服务新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改邮件服务' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$emailMod->EmailUpdate($data,$where);
		Ajax('邮件服务修改成功！');
	}
}
//删除邮件服务
else if ( $type == 'del' )
{
	$where['email_id'] = GetDelId();
	$emailMod->EmailDel($where);
	//写入操作记录
	SetOpLog( '删除了邮件服务' , 'system' , 'delete' , $table , $where);
	Ajax('邮件服务删除成功!');
}
//使用禁用邮件服务
else if ( $type == 'status' )
{
	$data['email_status'] = Request('status');
	$where['email_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$emailMod->EmailUpdate($data,$where);
	
	//写入操作记录
	SetOpLog( '邮件服务'.$msg , 'system' , 'update' , $table , $where);
	Ajax('邮件服务'.$msg);
}
//发送测试邮件
else if ( $type == 'test' )
{
	$data = $emailMod->EmailGetOne(Request('id'));
	if( $data )
	{
    	//发送测试邮件
        $msgSer = NewClass('msg',array('type'=>'email','id'=>'test'));
        $result = $msgSer->SendCode($data['email_name']);
		//写入操作记录
		SetOpLog( '发送了测试邮件' , 'system' , 'update' );
		if ( $result['code'] == '200' )
		{
			Ajax('邮件发送成功，请前往邮箱查看!');
		}
		else
		{
			Ajax('测试邮件发送失败!'.$result['msg'],300);
		}
	}
	else
	{
		Ajax('对不起，邮件配置不存在!',300);
	}
}
?>