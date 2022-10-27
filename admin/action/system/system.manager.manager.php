<?php
/**
* 后台管理员处理器
*
* @version        $Id: system.manager.manager.php 2016年4月6日 13:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@manager_manager';

if( Post('id') == '1')
{
	Ajax( '对不起，超级管理员无法修改！' , 300);
}

if ( $type == 'edit' || $type == "add" )
{
	if ( $post['manager_name'] == '' )
	{
		Ajax('对不起，登录账号必须填写！',300);
	}
	//设置where条件
	$where['manager_id'] = Post('id');
	$post = str::Escape($post , 'e');
	
	unset($post['id']);

	//新增菜单
	if( $type == "add" )
	{
		//查询账号是否存在
		$wheresql['table'] = $table;
		$wheresql['where']['manager_name'] = $post['manager_name'];
		$count = wmsql::GetCount($wheresql);
		if ( $count > 0 )
		{
			Ajax('对不起，账号已经存在了！',300);
		}
		
		if ( $post['manager_psw'] != $post['manager_cpsw'] )
		{
			Ajax('对不起，两次输入的密码不一致！',300);
		}
		else if ( $post['manager_psw'] == '')
		{
			Ajax('对不起，密码不能为空！',300);
		}
		
		//加密密码
		$post['manager_salt'] = str::GetSalt();
		$post['manager_psw'] = str::E($post['manager_psw'],$post['manager_salt']);
		//删除重复密码
		unset($post['manager_cpsw']);
		
		$where['manager_id'] = WMSql::Insert($table, $post);

		//写入操作记录
		SetOpLog( '新增了管理员账号'.Post('manager_name') , 'system' , 'insert' , $table , $where , $post );
		
		Ajax('账号新增成功!');
	}
	//修改菜单
	else
	{
		//检查是否修改的是超级管理员账号
		$wheresql['table'] = $table;
		$wheresql['where'] = $where;
		$managerData = wmsql::GetOne($wheresql);
		if( Session( 'admin_cid') != '0' && $managerData['manager_cid'] == '0' )
		{
			Ajax('对不起，您无法修改超级管理员的密码！',300);
		}
		//判断修改密码
		else if ( $post['manager_psw'] != $post['manager_cpsw'] && $post['manager_psw'] != '')
		{
			Ajax('对不起，两次输入的密码不一致！',300);
		}
		//需要修改密码
		else if( $post['manager_psw'] != '' )
		{
			$post['manager_salt'] = str::GetSalt();
			$post['manager_psw'] = str::E($post['manager_psw'],$post['manager_salt']);
		}
		//否则删除密码
		else
		{
			unset($post['manager_psw']);
		}
		unset($post['manager_cpsw']);
		unset($post['manager_name']);
		
		//写入操作记录
		SetOpLog( '修改了管理员账号'.Post('manager_name') , 'system' , 'update' , $table , $where , $post );
		
		WMSql::Update($table, $post, $where);
		Ajax('账号修改成功!');
	}
}
//账号状态设置
else if ( $type == 'status' )
{
	if( $post['id'] == '1')
	{
		Ajax( '对不起，超级管理员无法禁用！' , 300);
	}
	
	$status = str::CheckElse($post['status'], 0 , '0' , '1');
	$where['manager_id'] = $post['id'];
	$data['manager_status'] = $status;
	
	//状态判断
	switch ($status)
	{
		case "1":
			$statusText = '恢复';
			break;
				
		case "0":
			$statusText = '禁用';
			break;
	}
	
	//写入操作记录
	SetOpLog( $statusText.'了管理员账号' , 'system' , 'update' , $table , $where , $data );
	
	wmsql::Update($table, $data, $where);
	
	Ajax('账号'.$statusText.'成功!');
}
//删除管理员账号
else if ( $type == 'del' )
{
	if( $post['id'] == '1')
	{
		Ajax( '对不起，超级管理员无法删除！' , 300);
	}
	$where['manager_id'] = $post['id'];

	//写入操作记录
	SetOpLog( '删除了管理员账号' , 'system' , 'delete' , $table , $where);

	wmsql::Delete($table, $where);
	
	Ajax('管理员账号删除成功!');
}
?>