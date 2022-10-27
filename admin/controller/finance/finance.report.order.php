<?php
/**
* 销售报表结算订单控制器文件
*
* @version        $Id: finance.report.order.php 2021年08月07日 15:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$config = GetModuleConfig('user');
$manager = AdminNewClass('manager');
$listMod = NewModel('finance.finance_report_list');

//获取列表条件
$where['table'] = '@finance_report_order';
if( $orderField == '' )
{
	$where['order'] = 'order_id desc';
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>