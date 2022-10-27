<?php
/**
* 销售报表结算订单模型
*
* @version        $Id: finance_report_order.model.php 2021年08月07日 13:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_Report_OrderModel
{
	private $orderTable = '@finance_report_order';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 插入销售报表结算订单数据
	 * @param 参数1，必须，插入的数据
	 */
	function Insert( $data )
	{
		//财务配置
		$financeConfig = GetModuleConfig('finance' , true);
		
		$data['order_money'] = $data['order_gold2']/$financeConfig['rmb_to_gold2'];
		$data['order_time'] = time();
		return wmsql::Insert($this->orderTable, $data);
	}
}
?>