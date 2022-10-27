<?php
/**
* 获得订单详细信息
*
* @version        $Id: get_charge_order_list.php 2017年07月22日 15:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$orderSn = str::IsEmpty(Request('sn',Cookie('order_sn')),$lang['finance']['ordersn_err']);
$uid = user::GetUid();
$data = array();

//没有登录
if( $uid == 0 )
{
	$code = 300;
	$msg = $lang['finance']['no_login'];
}
else
{
	$code = 200;
	$msg = null;
	$orderMod = NewModel('finance.finance_order');
	//根据sn查询
	$where['charge_user_id'] = $uid;
	$where['charge_sn'] = $orderSn;
	$data = str::DelKey($orderMod->GetChargeOrderOne($where), 'charge_paysn');
	if(!$data)
	{
		$code = 201;
		$msg = $lang['finance']['order_no'];
	}
}
ReturnData($msg , $ajax , $code , $data);
?>