<?php
/**
* 财务订单模型
*
* @version        $Id: finance_order.model.php 2017年4月3日 14:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_OrderModel
{
	private $chargeOrderTable = '@finance_order_charge';
	private $cashOrderTable = '@finance_order_cash';
	
	/**
	 * 构造函数
	 */
	function __construct(){}


	/**
	 * 创建充值订单
	 * @param 参数1，必须，数组
	 */
	function CreateChargeOrder($data)
	{
		$data['charge_addtime'] = time();
		return wmsql::Insert($this->chargeOrderTable, $data);
	}
	
	/**
	 * 获得订单号
	 * @param 参数1，必须，数组
	 * @remark 12位年月日时分秒+4位毫秒+后4位id
	 */
	function GetChargeOrderSn($type)
	{
		return date('YmdHis').GetMtime(4).sprintf("%04d",substr(user::GetUid(),-4));
	}

	/**
	 * 根据条件查询一条获得订单数据
	 * @param 参数1，必须，查询条件
	 */
	function GetChargeOrderOne($wheresql=array())
	{
		$where['table'] = $this->chargeOrderTable;
		$where['where'] = $wheresql;
		return wmsql::GetOne($where);
	}
	/**
	 * 根据订单号获得订单数据
	 * @param 参数1，必须，订单号
	 */
	function GetChargeOrderBySn($sn)
	{
		$where['charge_sn'] = $sn;
		return $this->GetChargeOrderOne($where);
	}
	/**
	 * 根据条件查询获得订单数据
	 * @param 参数1，必须，查询条件
	 */
	function GetChargeOrderList($wheresql=array())
	{
		$where['table'] = $this->chargeOrderTable;
		$where['where'] = $wheresql;
		return wmsql::GetAll($where);
	}

	/**
	 * 修改订单的数据
	 * @param 参数1，必须，订单号
	 * @param 参数1，选填，查询条件
	 */
	function UpdateChargeOrder($data , $where)
	{
		//如果不是数组，就设置为订单号
		if( !is_array($where) )
		{
			$wheresql['charge_sn'] = $where;
		}
		else
		{
			$wheresql = $where;
		}
		return wmsql::Update($this->chargeOrderTable, $data, $wheresql);
	}
	
	


	/**
	 * 创建提现订单
	 * @param 参数1，必须，数组
	 */
	function CreateCashOrder($data)
	{
		$data['cash_time'] = time();
		if( !isset($data['cash_remark']) )
		{
			$data['cash_remark'] = '申请提现';
		}
		return wmsql::Insert($this->cashOrderTable, $data);
	}
}
?>