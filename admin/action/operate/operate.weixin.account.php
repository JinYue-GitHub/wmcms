<?php
/**
* 微信公众号处理器
*
* @version        $Id: operate.weixin.account.php 2019年03月09日 11:22  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$platformSer = NewClass('weixin_platform');
$accountMod = NewModel('operate.weixin_account');
$table = '@weixin_account';

//编辑公众号信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['account'], 'e' );
	$where = $post['id'];
	
	if ( $data['account_name'] == '' || $data['account_account'] == '' || $data['account_gid'] == '' )
	{
		Ajax('对不起，公众号名字、账号和原始id必须填写！',300);
	}
	else if( !str::Number(GetKey($data,'account_type')) || !str::Number(GetKey($data,'account_auth')) 
		|| !str::Number(GetKey($data,'account_main')) || !str::Number(GetKey($data,'account_follow')))
	{
		Ajax('对不起，公众号类型、是否认证、是否主号和强制关注必须选择！',300);
	}
	else if( $accountMod->CheckExists(array(
			'account_name'=>$data['account_name'],
			'account_type'=>$data['account_type']
			) , $where['account_id']) )
	{
		Ajax('对不起，该类型的公众号名字已经存在！',300);
	}
	
	//获取模版内容
	$data['account_welcome_temp'] = $platformSer->ResponseGetTemp('text',$data['account_welcome']);
	$data['account_default_temp'] = $platformSer->ResponseGetTemp('text',$data['account_default']);
	
	//新增数据
	if( $type == 'add' )
	{
		$opType = 'insert';
		$info = '新增了公众号'.$data['account_name'];
		$where['account_id'] = $accountMod->Insert($data);
	}
	//修改分类
	else
	{
		$opType = 'update';
		$info = '修改了公众号'.$data['account_name'];
		$accountMod->Update($data,$where['account_id']);
	}
	//写入操作记录
	SetOpLog( $info, 'system' , $opType , $table , $where , $data );
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['account_id'] = GetDelId();
	$accountMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了公众号' , 'system' , 'delete' , $table , $where);
	Ajax('公众号删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$msg = '隐藏';
	$where['account_id'] = GetDelId();
	$data['account_status'] = Request('status/i');
	if( Request('status') == '1')
	{
		$msg = '显示';
	}
	$accountMod->Update($data,$where['account_id']);
	//写入操作记录
	SetOpLog( $msg.'了公众号' , 'system' , 'update' , $table , $where);
	Ajax('公众号'.$msg.'成功!');
}
//设为主号
else if ( $type == 'main' )
{
	$id = Request('id/i');
	$where['account_id'] = $id;
	$data['account_main'] = Request('main/i');
	if( $accountMod->GetOne(array('account_main'=>1,'account_id'=>array('!=',$id))) )
	{
		Ajax('同时只能存在一个主号，如需设置当前公众号为主号请先取消之前的主号!',300);
	}
	else
	{
		$accountMod->Update($data,$id);
		//写入操作记录
		SetOpLog( '设置了公众号主号' , 'system' , 'update' , $table , $where);
		Ajax('主号设置/取消成功!');
	}
	
}
//接入检查
else if ( $type == 'check' )
{
	Ajax('在公众号后台填写地址和参数后即可进行自动检查!');
}
?>