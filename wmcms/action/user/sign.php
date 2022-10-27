<?php
/**
* 签到操作处理
*
* @version        $Id: sign.php 2016年5月28日 22:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @update 		  2021年01月30日 13:07
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$uid = user::GetUid();
//是否登录了
str::EQ( $uid , 0 , $lang['user']['no_login'] );
//签到已经关闭
str::EQ( $userConfig['sign_open'] , 0 , $lang['user']['sign_close'] );


//查询签到信息
$signMod = NewModel('user.sign');
$ticketMod = NewModel('user.ticket');
$signLogMod = NewModel('user.sign_log');

//获取用户的签到信息
$signMod->userId = user::GetUid();
//今日签到排名
$data['sign_rank'] = $signMod->GetCount(array('sign_time'=>array('>',strtotime(date('Y-m-d')))))+1;
//签到日志数据
$logData['log_user_id'] = $signMod->userId;
$logData['log_gold1'] = 0;
$logData['log_gold2'] = 0;
$logData['log_exp'] = 0;
$logData['log_rec'] = 0;
$logData['log_month'] = 0;
$logData['log_rank'] = $data['sign_rank'];

//如果没有签到记录就插入一条
$signData = $signMod->GetLastOne();
if ( !$signData )
{
	$result = $signMod->Insert($data);
}
//有签到记录就修改
else
{
	//如果签到时间大于今0点就已经签到了
	if ( $signData['sign_time'] > strtotime('today') )
	{
		ReturnData( $lang['user']['sign_exist'] , $ajax );
	}
	
	//如果是连续签到
	if( date("Ymd",time())-1 == date("Ymd",$signData['sign_time']) )
	{
		$data['sign_con'] = $signData['sign_con']+1;
	}
	//不是就重置为1
	else
	{
		$data['sign_con'] = 1;
	}
	//修改的数据
	$data['sign_sum'] = $signData['sign_sum']+1;
	$data['sign_pretime'] = $signData['sign_time'];
	$data['sign_prerank'] = $signData['sign_rank'];
	$data['sign_time'] = time();
	//保存修改数据
	$result = $signMod->Save($data);
}


//每日签到奖励是否开启
if( $userConfig['sign_open'] == '1' )
{
	//签到日志奖励设置
	$logData['log_gold1'] = $userConfig['sign_gold1'];
	$logData['log_gold2'] = $userConfig['sign_gold2'];
	$logData['log_exp'] = $userConfig['sign_exp'];
	//更新奖励操作
	$rewardData['gold1'] = $userConfig['sign_gold1'];
	$rewardData['gold2'] = $userConfig['sign_gold2'];
	$rewardData['exp'] = $userConfig['sign_exp'];
	$log['module'] = 'user';
	$log['type'] = 'sign';
	$log['remark'] = $lang['user']['ticket_sign_remark'];
	$userMod->RewardUpdate( $uid , $rewardData , $log );
	
	//签到日志奖励设置
	$logData['log_rec'] = $userConfig['sign_rec'];
	$logData['log_month'] = $userConfig['sign_month'];
	//更新推荐票
	$ticketData['rec'] = $userConfig['sign_rec'];
	$ticketData['month'] = $userConfig['sign_month'];
	$ticketData['remark'] =  $lang['user']['ticket_sign_remark'];
	$ticketMod->Update( $uid , $ticketData);
}

//保存签到成功
if( $result )
{
	//插入签到日志
	$signLogMod->Insert($logData);
	ReturnData($lang['user']['operate']['sign']['success'] , $ajax , 200,$logData);
}
else
{
	ReturnData($lang['user']['operate']['sign']['fail'] , $ajax);
}
?>