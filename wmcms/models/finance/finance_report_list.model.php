<?php
/**
* 销售报表结算订单模型
*
* @version        $Id: finance_report_list.model.php 2021年08月07日 13:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_Report_ListModel
{
	private $orderTable = '@finance_report_list';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 插入销售报表关联结算订单数据
	 * @param 参数1，必须，插入的数据
	 */
	function InsertAll( $data )
	{
		return wmsql::InsertAll($this->orderTable, $data);
	}
	
	/**
	 * 根据订单id获得所有结算记录
	 * @param 参数1，必须 $oid
	 * @return Ambigous <boolean, mixed, multitype:>
	 */
	function GetByOid($oid)
	{
		$where['table'] = $this->orderTable;
		$where['where']['list_order_id'] = $oid;
		return wmsql::GetAll($where);
	}
	
	/**
	 * 根据订单获得rid集合
	 * @param unknown $oid
	 * @return Ambigous
	 */
	function GetRidsByOid($oid)
	{
		$list = $this->GetByOid($oid);
		$rids = str::ArrToStr($list,',',null,null,'list_report_id');
		return $rids;
	}
}
?>