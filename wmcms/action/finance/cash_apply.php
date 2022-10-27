<?php
/**
* 新增提现请求处理
*
* @version        $Id: cash_apply.php 2017年4月5日 20:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$money = str::Int(Request('money'));
#用户金币2
$userGold2 = user::GetGold2();
#用户余额
$userMoney = user::GetMoney();
#开启了金币2转换余额功能
if( $financeConfig['gold2_to_money_open'] == 1 )
{
	#用户可使用的金币2
	$canUseGold2 = $financeConfig['gold2_to_money']*$userGold2+$userMoney;
}
else
{
	$canUseGold2 = $userMoney;
}
$gold2 = $money/$financeConfig['gold2_to_money'];
#提现金额超出用户余额数量
$beyondMoney = $money-$userMoney;

//是否开关提现
if ( $financeConfig['cash_open'] <> '1')
{
	ReturnData( $lang['finance']['action']['cash_close'] , $ajax );
}
//最低金额大于了提现金额
else if($financeConfig['cash_lowest'] > $money)
{
	ReturnData( $lang['finance']['action']['cash_lowest_err'] , $ajax );
}
//金币2小于提现金额
else if($canUseGold2 < $money)
{
	ReturnData( $lang['finance']['action']['cash_money_no'] , $ajax );
}
else
{
	$financeMod = NewModel('user.finance');
	
	//如果没有填写银行卡或者支付宝信息。
	$financeData = $financeMod->GetFinance($uid);
	if( $financeData['finance_alipay'] == '' && $financeData['finance_bankcard'] == '' )
	{
		ReturnData( $lang['finance']['action']['cash_account_no'] , $ajax );
	}

	#开启了才显示资金兑换记录
	if( $financeConfig['gold2_to_money_open'] == 1 && $beyondMoney>0)
	{
		//修改用户资金变更记录
		$logData['module'] = 'finance';
		$logData['type'] = 'cash_apply';
		$logData['uid'] = $uid;
		$logData['remark'] = '提现申请！';
		$userMod->CapitalChange( $uid , $logData , 0 , $beyondMoney/$financeConfig['gold2_to_money'] , '2' );
		
		//更新用户余额
		$userMod->UpdateMoney($uid,0);
	}
	#否则直接更新余额
	else
	{
		$userMod->UpdateMoney($uid,$money,'2');
	}
	
	//插入申请记录
	$orderData['cash_user_id'] = $uid;
	$orderData['cash_money'] = $money;
	$orderData['cash_real'] = $money*((100-$financeConfig['cash_cost'])/100);
	$orderData['cash_cost'] = $financeConfig['cash_cost'];
	$result = $orderMod->CreateCashOrder($orderData);

	//插入成功
	if( $result )
	{
		ReturnData( $lang['operate']['cash_charge']['success'] , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['operate']['cash_charge']['fail'] , $ajax );
	}
}
?>