<?php
/**
* 修改财务信息
*
* @version        $Id: upfinance.php 2016年12月25日 20:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$realName = str::LNC( Post('realname') , $lang['system']['par']['name_err'] , '2,10');
$cardId = str::CheckCardId( Post('cardid') , $lang['system']['par']['cardid_err']);
$address = str::DelHtml(str::LNC( Post('address') , $lang['system']['par']['address_err'] , '5,30'));
$zipCode = str::Int( Post('zipcode'), $lang['user']['zipcode_err'] );
$alipay = str::DelHtml(Post('alipay'));
$bank = str::DelHtml(Post('bank'));
$bankMaster = str::DelHtml(Post('bankmaster'));
$bankCard = str::Int(Post('bankcard'));
$bankAddress = str::DelHtml(Post('bankaddress'));

	
//银行卡和支付宝必须填写一种
if( $alipay == '' && ($bankMaster == '' || $bankCard == '' || $bank == '' || $bankAddress == '') )
{
	ReturnData( $lang['user']['pay_no'], $ajax);
}
else
{
	//设置财务数据
	$data['finance_realname'] = $realName;
	$data['finance_cardid'] = $cardId;
	$data['finance_address'] = $address;
	$data['finance_zipcode'] = $zipCode;
	$data['finance_bank'] = $bank;
	$data['finance_bankaddress'] = $bankAddress;
	$data['finance_bankcard'] = $bankCard;
	$data['finance_bankmaster'] = $bankMaster;
	$data['finance_alipay'] = $alipay;
	
	$financeMod = NewModel('user.finance');
	$financeData = $financeMod->GetFinance();
	//如果不存在就插入数据
	if( !$financeData )
	{
		$data['finance_user_id'] = user::GetUid();
		$result = $financeMod->InsertFinance($data);
	}
	//修改财务信息
	else
	{
		$result = $financeMod->UpdateFinance($data);
	}

	//操作成功或者失败
	if( $result )
	{
		ReturnData(  $lang['user']['operate']['upfinance']['success'] , $ajax , 200);
	}
	else
	{
		ReturnData(  $lang['user']['operate']['upfinance']['fail'] , $ajax);
	}
}
?>