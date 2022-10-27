<?php
/**
* 销售报表订单模型
*
* @version        $Id: finance_report.model.php 2021年7月31日 11:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_ReportModel
{
	private $reportOrderTable = '@finance_report';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 根据id获得结算数据总和
	 * @param 参数1，必须 $ids
	 * @return Ambigous <boolean, mixed, multitype:>
	 */
	function GetSum($ids)
	{
		$where['table'] = $this->reportOrderTable;
		$where['where']['report_id'] = $ids;
		//金币1总数
		$where['field'] = 'report_gold1';
		$data['report_gold1'] = wmsql::GetSum($where);
		//金币2总数
		$where['field'] = 'report_gold2';
		$data['report_gold2'] = wmsql::GetSum($where);
		return $data;
	}

	/**
	 * 结算报表
	 * @param 参数1，必须 $ids
	 * @param 参数2，必须，结算员ID $aid
	 * @return boolean
	 */
	function Settlement($ids,$aid)
	{
		//获得结算数据统计
		$sumData = $this->GetSum($ids);
		
		$where['report_id'] = $ids;
		$data['report_settlement'] = 1;
		$data['report_settlement_id'] = $aid;
		$data['report_settlement_time'] = time();
		$result = wmsql::Update($this->reportOrderTable, $data, $where);
		//结算成功
		if( $result )
		{
			//插入订单
			$orderData['order_gold1'] = $sumData['report_gold1'];
			$orderData['order_gold2'] = $sumData['report_gold2'];
			$orderData['order_admin_id'] = $aid;
			$orderMod = NewModel('finance.finance_report_order');
			$orderId = $orderMod->Insert($orderData);
			
			//插入关联订单
			$listWhereIds = $ids;
			if( $ids[0] == 'lin' )
			{
				$listWhereIds = $ids[1];
			}
			foreach (explode(',', $listWhereIds) as $v)
			{
				$listData[] = array(
					'list_report_id'=>$v,
					'list_order_id'=>$orderId,
				);
			}
			$listMod = NewModel('finance.finance_report_list');
			$orderId = $listMod->InsertAll($listData);
		}
		return true;
	}
	
	/**
	 * 插入销售报表
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，资金变更日志
	 * @param 参数3，必须，变更的金币1
	 * @param 参数4，必须，变更的金币2
	 * @param 参数5，选填，变更类型，是加还是减
	 */
	function InsertReportByLog( $uid , $log , $gold1='0' , $gold2='0' , $type = '1' )
	{
		//目前为type为1的收入才写入报表
		if( $type == '1' )
		{
			if( !isset($log['cid']) )
			{
				$log['cid'] = '0';
			}
			$data['report_cid'] = $log['cid'];
			$data['report_remark'] = isset($log['remark'])?$log['remark']:'';
			$data['report_module'] = $log['module'];
			$data['report_type'] = $log['type'];
			$data['report_user_id'] = $uid;
			$data['report_gold1'] = $gold1;
			$data['report_gold2'] = $gold2;
			$data['report_time'] = time();
			return wmsql::Insert($this->reportOrderTable, $data);
		}
		return true;
	}
}
?>