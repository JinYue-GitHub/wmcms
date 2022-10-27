<?php
/**
* 管理员账号处理器
*
* @version        $Id: system.safe.account.php 2016年4月6日 16:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@manager_manager';

//修改管理员账号
if ( $type == 'uppsw' )
{
	if ( $post['bpsw'] == '' )
	{
		Ajax( '对不起，原始密码不能为空！' , 300 );
	}
	else if( $post['psw'] == '' )
	{
		Ajax( '对不起，新密码不能为空！' , 300 );
	}
	else if( $post['psw'] != $post['cpsw'] )
	{
		Ajax( '对不起，两次输入的密码不一致！' , 300 );
	}
	
	//检查原始密码是否一致
	$where['table'] = $table;
	$where['where']['manager_id'] = Session('admin_id');
	$data = wmsql::GetOne( $where );
	
	if ( $data['manager_psw'] == str::E($post['bpsw'],$data['manager_salt']) )
	{
		$data['manager_salt'] = str::GetSalt();
		$data['manager_psw'] = str::E($post['psw'],$data['manager_salt']);
		wmsql::Update($table, $data, $where['where']);

		//写入操作记录
		SetOpLog( '修改了账户密码' , 'system' , 'update');
		
		Session( 'admin_account' , 'delete' );
		Ajax('密码修改成功，请重新登录!');
	}
	else
	{
		Ajax( '对不起，原始密码错误！' , 300 );
	}
}
?>