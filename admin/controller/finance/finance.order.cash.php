<?php
/**
* 提现订单控制器文件
*
* @version        $Id: finance.order.cash.php 2017年4月6日 22:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$config = GetModuleConfig('user');
$cashMod = NewModel('finance.finance_cash');

//接受post数据
$name = Request('name');

//获取列表条件
$where['table'] = '@finance_order_cash';
$where['field'] = '@finance_order_cash.*,user_nickname';
$where['left']['@user_user'] = 'cash_user_id=user_id';
if( $orderField == '' )
{
	$where['order'] = 'cash_id desc';
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>