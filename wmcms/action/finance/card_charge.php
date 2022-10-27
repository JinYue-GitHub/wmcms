<?php
/**
* 充值卡使用操作操作
*
* @version        $Id: card_charge.php 2017年4月2日 22:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//使用账号类型
$accountType = str::Int( Request('accounttype' , 0) );
//对好友使用的账号
$account = trim(Request('account'));
//重复
$reAccount = trim(Request('reaccount'));
//卡密
$cardKey = trim(Request('cardkey'));

//卡密是否存在
if( $financeConfig['recharge_open'] == 0 )
{
	ReturnData( $lang['finance']['action']['charge_close'] , $ajax );
}
else if ( strlen($cardKey) != 16 && strlen($cardKey) != 32)
{
	ReturnData( $lang['finance']['action']['cardkey_err'] , $ajax );
}
//如果为好友充值则判断账号是否正确
else if ( $accountType == 1 && ( $account != $reAccount || $account == '' || str::CheckLen( $account , '4,16') == false) )
{
	ReturnData( $lang['finance']['action']['account_err'] , $ajax );
}
//数据正常
else
{
	$cardMod = NewModel('user.card');
	//使用卡号
	$option['accounttype'] = $accountType;
	$option['account'] = $account;
	$option['reaccount'] = $reAccount;
	$option['cardkey'] = $cardKey;
	$result = $cardMod->UseCard($uid , $option);
	switch ($result)
	{
		case '201':
			ReturnData( $lang['finance']['action']['cardkey_err'] , $ajax );
			break;
			
		case '202':
			ReturnData( $lang['finance']['action']['cardkey_use'] , $ajax );
			break;
			
		case '203':
			ReturnData( $lang['finance']['action']['account_no'] , $ajax );
			break;
			
		default:
			ReturnData( $lang['operate']['card_charge']['success'] , $ajax , 200 );
			break;
	}
}
?>