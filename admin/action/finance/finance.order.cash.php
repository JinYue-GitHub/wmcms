<?php
/**
* 提现订单处理器
*
* @version        $Id: finance.order.cash.php 2017年4月6日 22:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cashTable = '@finance_order_cash';

//删除记录
if ( $type == 'del'  )
{
	$where['cash_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了提现申请！' , 'finance' , 'delete' , $cashTable , $where);
	wmsql::Delete($cashTable , $where);
	
	Ajax('提现申请批量删除成功!');
}
//清空数据记录
else if ( $type == 'clear')
{
	SetOpLog( '清空了所有提现申请！' , 'finance' , 'delete' , $cashTable);
	wmsql::Delete($cashTable);
	Ajax('提现申请全部清空成功！');
}
//审核操作
else if ( $type == 'status')
{
	$cashMod = NewModel('finance.finance_cash');
	$id = str::Int(Request('id'));
	$status = str::Int(Request('status'));
	$where['cash_id'] = $id;
	$data['cash_status'] = $status;
	
	//查询是否存在
	$cashData = $cashMod->GetById($id);
	if( $cashData )
	{
		$cashMod->UpdateStatus($id , $status);
		if( $status == 1)
		{
			SetOpLog( '同意了提现申请！' , 'finance' , 'delete' , $cashTable , $where , $data);
			Ajax('同意提现申请成功！');
		}
		else
		{
			$config = GetModuleConfig('finance' , true);
			//把申请提现的金钱还给用户
			$userMod = NewModel('user.user');
			$logData['module'] = 'finance';
			$logData['type'] = 'cash_refuse';
			$logData['uid'] = $cashData['cash_user_id'];
			$logData['remark'] = '提现拒绝返还！';
			$userMod->CapitalChange( $cashData['cash_user_id'] , $logData , 0 , $cashData['cash_money']/$config['gold2_to_money']);
			SetOpLog( '拒绝了提现申请！' , 'finance' , 'delete' , $cashTable , $where , $data);
			Ajax('拒绝提现申请成功！');
		}
	}
	else
	{
		Ajax('对不起，该提现申请不存在！');
	}
}
?>