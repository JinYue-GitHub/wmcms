<?php
/**
* 充值订单处理器
*
* @version        $Id: finance.order.charge.php 2017年4月3日 20:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$logTable = '@finance_order_charge';

//删除记录
if ( $type == 'del'  )
{
	$where['charge_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了充值订单！' , 'finance' , 'delete' , $logTable , $where);
	wmsql::Delete($logTable , $where);
	
	Ajax('充值订单批量删除成功!');
}
//清空数据记录
else if ( $type == 'clear')
{
	SetOpLog( '清空了所有充值订单！' , 'finance' , 'delete' , $logTable);
	wmsql::Delete($logTable);
	Ajax('充值订单全部清空成功！');
}
?>