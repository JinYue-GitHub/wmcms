<?php
/**
* 用户处理器
*
* @version        $Id: user.user.php 2016年5月5日 16:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@user_user';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$financeMod = NewModel('user.finance');
	//用户数据
	$data = str::Escape( $post['user'], 'e' );
	//财务数据
	$financeData = str::Escape( $post['finance'], 'e' );
	$where['user_id'] = $post['user_id'];
	$data['user_displaytime'] = strtotime($data['user_displaytime']);
	$data['user_logintime'] = strtotime($data['user_logintime']);
	$data['user_regtime'] = strtotime($data['user_regtime']);
	//这里不能修改金币
	unset($data['user_gold1']);
	unset($data['user_gold2']);
	unset($data['user_money']);
		
	if( $data['user_display'] == '2')
	{
		$data['user_displaytime'] = strtotime($data['user_displaytime']);
	}

	if ( $data['user_name'] == '' || $data['user_nickname'] == '' || $data['user_email'] == '' )
	{
		Ajax('对不起，用户账号、昵称和邮箱地址必须填写！',300);
	}
	else if ( str::CheckEmail($data['user_email']) == false )
	{
		Ajax('对不起，邮箱格式错误！',300);
	}
	if ( $data['user_psw'] != '' )
	{
		$data['user_salt'] = str::GetSalt();
		$data['user_psw'] = str::E($data['user_psw'],$data['user_salt']);
	}

	
	//用户名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['user_id'] = array('<>',$where['user_id']);
	$wheresql['where']['user_name'] = $data['user_name'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该用户名已经注册了！',300);
	}
	
	//用户邮箱检查
	unset($wheresql['where']['user_name']);
	$wheresql['table'] = $table;
	$wheresql['where']['user_id'] = array('<>',$where['user_id']);
	$wheresql['where']['user_email'] = $data['user_email'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该邮箱已经注册了！',300);
	}
	
	//用户昵称检查
	unset($wheresql['where']['user_email']);
	$wheresql['table'] = $table;
	$wheresql['where']['user_id'] = array('<>',$where['user_id']);
	$wheresql['where']['user_nickname'] = $data['user_nickname'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，用户昵称已经被注册了！',300);
	}
	
	
	//新增数据
	if( $type == 'add' )
	{
		if ( $data['user_psw'] == '' )
		{
			Ajax('对不起，用户密码不能为空！',300);
		}
		//插入作者信息
		$where['user_id'] = wmsql::Insert($table, $data);

		//插入财务信息
		$financeData['finance_user_id'] = $where['user_id'];
		$financeMod->InsertFinance($financeData);
		
		//写入操作记录
		$info = '恭喜您，用户添加成功！';
		SetOpLog( '新增了用户'.$data['user_name'] , 'user' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		if ( $data['user_psw'] == '' )
		{
			unset($data['user_psw']);
		}
		//修改用户信息
		wmsql::Update($table, $data, $where);
		
		//修改财务信息
		$financeMod->UpdateFinance($financeData , $where['user_id']);
		
		//写入操作记录
		$info = '恭喜您，用户修改成功！';
		SetOpLog( '修改了用户'.$data['user_name'] , 'user' , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del')
{
	$where['user_id'] = GetDelId();
	wmsql::Delete($table , $where);
	wmsql::Delete('@user_apilogin' , array('api_uid'=>GetDelId()));
	//写入操作记录
	SetOpLog( '删除了用户' , 'user' , 'delete' , $table , $where);
	Ajax('用户删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['user_status'] = Request('status');
	$where['user_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了用户' , 'user' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('用户'.$msg.'成功!');
}
//用户金币奖惩操作
else if ( $type == 'reward' )
{
	$id = Request('id');
	$settype = Request('settype');
	$gold1 = abs(Request('gold1'));
	$gold2 = abs(Request('gold2'));
	$money = abs(Request('money'));
	$remark = Request('remark');

	if( !str::Number($id) )
	{
		Ajax('用id错误!',300);
	}
	else if( $gold1 == '0' && $gold2 == '0' && $money == '0')
	{
		Ajax('奖惩金币不能为空!',300);
	}
	else
	{
		//查询用户是否存在
		$wheresql['table'] = $table;
		$wheresql['where']['user_id'] = $id;
		$count = wmsql::GetCount($wheresql);
		
		//如果用户不存在
		if( $count < 1 )
		{
			Ajax('用信息不存在!',300);
		}
		else
		{
			$operator = '+';
			//奖励金币
			if( $settype == '0')
			{
				$operator = '-';
			}
			//修改用户的金币
			$data['user_gold1'] = array($operator,$gold1);
			$data['user_gold2'] = array($operator,$gold2);
			$data['user_money'] = array($operator,$money);
			wmsql::Update($table, $data, $wheresql['where']);

			//写入操作记录
			SetOpLog( $remark , 'user' , 'insert' , $table , $wheresql['where'] , $data );

			//插入金币奖惩记录
			$incomeData['log_module'] = 'system';
			$incomeData['log_type'] = 'reward';
			$incomeData['log_user_id'] = $id;
			$incomeData['log_gold1'] = $operator.$gold1;
			$incomeData['log_gold2'] = $operator.$gold2;
			$incomeData['log_remark'] = $remark;
			$incomeData['log_time'] = time();
			wmsql::Insert('@user_finance_log', $incomeData);
			
			//插入消息
			$userConfig = AdminInc('user');
			$msgMod = NewModel('user.msg');
			$msg = $remark;
			$msg .= '<br/>'.$operator.$money.$userConfig['money_name'].'!';
			$msg .= '<br/>'.$operator.$gold1.$userConfig['gold1_name'].'!';
			$msg .= '<br/>'.$operator.$gold2.$userConfig['gold2_name'].'!';
			$msgMod->Insert($id , $msg);
			
			Ajax($remark.'成功!');
		}
	}
}
?>