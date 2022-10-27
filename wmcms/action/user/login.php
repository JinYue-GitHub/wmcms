<?php
/**
* 用户登录请求处理
*
* @version        $Id: login.php 2016年5月29日 10:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2018年3月25日 20:23  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//不存在api登录数据判断提交的用户数据是否正确
$apiUser = unserialize(str::Encrypt(Post('apiuser'),'D'));
if( !is_array($apiUser) )
{
	FormTokenCheck();
	FormCodeCheck('code_user_login');
}

//判断是否开启登录
str::EQ( $userConfig['login_open'], 0, $lang['user']['login_close']);

//接受参数
$name = str::IsEmpty( Post('name') , $lang['user']['name_no'] );
$psw = str::IsEmpty( Post('psw') , $lang['user']['psw_no'] );
$remember = str::IsTrue( Post('remember') , 1);
$time = str::Int( Post('time') , '' ,2592000);

//验证参数
//账号长度和账号格式
str::CheckLen( $name , '4,50' , $lang['user']['name_len']  );
//str::LN( $name, $lang['user']['name_err'] );
//密码长度和密码格式
str::CheckLen( $psw , '6,16' , $lang['user']['psw_len']  );
str::NCN( $psw, $lang['user']['psw_err'] );



//new一个登录日志模型
$logMod = NewModel('user.log');
$loginCount = $logMod->GetCount();
//检查是否达到最高错误次数
if( $loginCount >= $C['config']['web']['user_login_error_number'] )
{
	$info = tpl::Rep( array('封锁时间'=>$C['config']['web']['user_login_error_time']) ,$lang['user']['login_err'] );
	ReturnData( $info );
}

//手机号登录
if( str::CheckTel($name) )
{
    $where['user_tel'] = $name;
    //$where['user_teltrue'] = 1;
}
//邮箱登录
else if( str::CheckEmail($name) )
{
    $where['user_email'] = $name;
    //$where['user_emailtrue'] = 1;
}
//账号登录
else
{
    $where['user_name'] = $name;
}
$data = $userMod->GetOne($where);
//设置用户的数据
user::$user = $data;

//账号存在，密码正确
if ( $data && $data['user_psw'] == str::E($psw,$data['user_salt']) )
{
	//手机号未认证
	if( isset($where['user_tel']) && $data['user_teltrue'] == '0' )
	{
		ReturnData( $lang['user']['account_tel_0'] );
	}
	//邮箱未认证
	else if( isset($where['user_email']) && $data['user_emailtrue'] == '0' )
	{
		ReturnData( $lang['user']['account_email_0'] );
	}
	//审核中
	else if( $data['user_status'] == '0' )
	{
		ReturnData( $lang['user']['account_status_0'] );
	}
	//全站封禁
	else if( $data['user_display'] == '0' )
	{
		ReturnData( $lang['user']['account_display_0'] );
	}
	//限时封禁
	else if( $data['user_display'] == '2' )
	{
		//正在封禁的时间段内
		if ( $data['user_displaytime'] > time() )
		{
			$info = tpl::Rep( array('时间'=>date('Y-m-d H:i:s',$data['user_displaytime'])) ,$lang['user']['account_display_2'] );
			ReturnData( $info );
		}
		//封禁的时间小于当前时间就解禁
		else
		{
			$userMod->SaveDisplay();
		}
	}
	
	//如果是接口登录就绑定账号
	if( is_array($apiUser) )
	{
		//清除api登录数据
		ClearApiLogin();
		if( !$userMod->InsertApiLogin($data['user_id'] , $apiUser) )
		{
			ReturnData( $lang['user']['api_err'] );
		}
	}

	//是今日首次登录就进行奖励
	$rewardData = array();
	if ( $data['user_logintime'] < strtotime('today') )
	{
		//修改登录时间和赠送的道具
		$rewardData['gold1'] = $userConfig['login_gold1'];
		$rewardData['gold2'] = $userConfig['login_gold2'];
		$rewardData['exp'] = $userConfig['login_exp'];
		$result = $userMod->EveryDayLogin( $data['user_id'] , $rewardData );
		
		//更新推荐票
		$ticketMod = NewModel('user.ticket');
		$lvData = user::GetLV();
		$ticketData['rec'] = $lvData['level_rec'] + $userConfig['login_rec'];
		$ticketData['month'] = $lvData['level_month'] + $userConfig['login_month'];
		$ticketData['remark'] =  $lang['user']['ticket_login_remark'];
		$ticketMod->Update( $data['user_id'] , $ticketData,1,$userConfig);
	}
	//开启了插入登录记录
	if ( $userConfig['login_log'] == '1' )
	{
		$logMod->Insert(1,1,$data['user_id']);
	}
	//检查是否需要更新密码盐
	if( empty($data['user_salt']) )
	{
		$salt = str::GetSalt();
		$psw = str::E($psw,$salt);
		$userMod->Save(array('user_psw'=>$psw,'user_salt'=>$salt),$data['user_id']);
	}
	else
	{
		$psw = str::E($psw,$data['user_salt']);
	}
	
	//是否记住登录
	$saveTime = $time;
	if ( !$remember )
	{
		$saveTime = '';
	}
	//写入登录属性
	Cookie('user_account' , str::A($data['user_name'], $psw,time()+$time) , $saveTime );
	Cookie('user_nickname' , $data['user_nickname'] , $saveTime );
	
	//返回提示
	$info = GetInfo($lang['user']['operate']['login'] , 'user_home');
	$code = 200;

	//表单token删除
	FormDel();
	//处理返回用户信息
	$data = ProcessReturnUser($data);
	ReturnData( $info , $ajax , $code ,$data);

}
//账号存在，密码错误
else if ( $data )
{
	//开启了插入登录记录
	if ( $userConfig['login_log'] == '1' )
	{
		$logMod->Insert(1,2,$data['user_id'],$lang['user']['logpsw_err']);
	}
	
	ReturnData( $lang['user']['psw_exist'] );
}
//账号不存在
else
{
	//开启了插入登录记录
	if ( $userConfig['login_log'] == '1' )
	{
		$logMod->Insert(1,0,0,$lang['user']['account_exist']);
	}
	ReturnData( $lang['user']['account_exist'] );
}
?>