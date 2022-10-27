<?php
/**
* 提现申请模型
*
* @version        $Id: finance_cash.model.php 2017年4月6日 22:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_CashModel
{
	private $cashTable = '@finance_order_cash';
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 获得订单的状态
	 * @param 参数1，必须，订单状态
	 * @return string
	 */
	function GetStatus($status)
	{
		switch ($status)
		{
			case '1':
				$status = '已处理';
				break;
			case '2':
				$status = '已拒绝';
				break;
			default:
				$status = '待处理';
				break;
		}
		return $status;
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，订单id
	 */
	function GetById($id)
	{
		$where['table'] = $this->cashTable;
		$where['where']['cash_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 获得提现订单数据
	 * @param 参数1，必须，查询条件
	 */
	function GetCashList($wheresql=array())
	{
		$where['table'] = $this->cashTable;
		$where['where'] = $wheresql;
		return wmsql::GetAll($where);
	}
	


	/**
	 * 获得一条数据
	 * @param 参数1，必须，订单id
	 * @param 参数2，必须，状态
	 */
	function UpdateStatus($id , $status)
	{
		$where['cash_id'] = $id;
		$data['cash_status'] = $status;
		return wmsql::Update($this->cashTable, $data, $where);
	}
}
?>