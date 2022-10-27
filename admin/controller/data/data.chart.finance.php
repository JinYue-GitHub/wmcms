<?php
/**
* 财务统计控制器
*
* @version        $Id: data.chaprt.finance.php 2017年8月7日 21:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//数据
$data = array();
$data['today'] = array('money'=>0,'charge'=>0,'cash'=>0,);
$data['yesterday'] = array('money'=>0,'charge'=>0,'cash'=>0,);
$data['all'] = array('money'=>0,'charge'=>0,'cash'=>0,);

//充值模型
$chargeMod = NewModel('finance.finance_charge');
//充值订单数据
$chargeOrderMod = NewModel('finance.finance_order');
$chargeList = $chargeOrderMod->GetChargeOrderList();
if($chargeList)
{
	foreach ($chargeList as $k=>$v)
	{
		//今天订单金额
		if( date('Y-m-d',$v['charge_addtime']) == date('Y-m-d',time()) )
		{
			$data['today']['money'] += $v['charge_money'];
		}
		//今日充值金额和今日实时充值
		if( $v['charge_status'] == '1' && date('Y-m-d',$v['charge_addtime']) == date('Y-m-d',time()) )
		{
			$data['today']['charge'] += $v['charge_money'];
			//今日充值金额
			if( isset($data['todayTime'][date('G',$v['charge_addtime'])]) )
			{
				$data['todayTime'][date('G',$v['charge_addtime'])] += $v['charge_money'];
			}
			else
			{
				$data['todayTime'][date('G',$v['charge_addtime'])] = $v['charge_money'];
			}
			//今日充值次数
			if( isset($data['todayTypeCount'][$v['charge_type']]) )
			{
				$data['todayTypeCount'][$v['charge_type']] += 1;
			}
			else
			{
				$data['todayTypeCount'][$v['charge_type']] = 1;
			}
			//今日充值类型
			if( isset($data['todayTypeMoney'][$v['charge_type']]) )
			{
				$data['todayTypeMoney'][$v['charge_type']] += $v['charge_money'];
			}
			else
			{
				$data['todayTypeMoney'][$v['charge_type']] = $v['charge_money'];
			}
		}
		
		//昨天订单金额
		if( date('Y-m-d',$v['charge_addtime']) == date("Y-m-d",strtotime("-1 day")) )
		{
			$data['yesterday']['money'] += $v['charge_money'];
		}
		//昨日充值金额和昨日实时充值
		if( $v['charge_status'] == '1' && date('Y-m-d',$v['charge_addtime']) == date("Y-m-d",strtotime("-1 day")) )
		{
			$data['yesterday']['charge'] +=$v['charge_money'];
			if( isset($data['yesterdayTime'][date('G',$v['charge_addtime'])]) )
			{
				$data['yesterdayTime'][date('G',$v['charge_addtime'])] += $v['charge_money'];
			}
			else
			{
				$data['yesterdayTime'][date('G',$v['charge_addtime'])] = $v['charge_money'];
			}
		}
		
		//总订单金额
		$data['all']['money'] += $v['charge_money'];
		//总充值金额
		if( $v['charge_status'] == '1' )
		{
			$data['all']['charge'] += $v['charge_money'];
			//总充值次数
			if( isset($data['allTypeCount'][$v['charge_type']]) )
			{
				$data['allTypeCount'][$v['charge_type']] += 1;
			}
			else
			{
				$data['allTypeCount'][$v['charge_type']] = 1;
			}
			//总充值金额
			if( isset($data['allTypeMoney'][$v['charge_type']]) )
			{
				$data['allTypeMoney'][$v['charge_type']] += $v['charge_money'];
			}
			else
			{
				$data['allTypeMoney'][$v['charge_type']] = $v['charge_money'];
			}
		}
	}
}

//循环实时数据
for($i=1;$i<=24;$i++){
	if( empty($data['todayTime'][$i]) )
	{
		$data['todayTime'][$i]=0;
	}
	if( empty($data['yesterdayTime'][$i]) )
	{
		$data['yesterdayTime'][$i]=0;
	}
}

//提现订单数据
$cashOrderMod = NewModel('finance.finance_cash');
$cashList = $cashOrderMod->GetCashList();
if($cashList)
{
	foreach ($cashList as $k=>$v)
	{
		if( $v['cash_status'] == '1' )
		{
			//今天提现金额
			if( date('Y-m-d',$v['cash_handletime']) == date('Y-m-d',time()) )
			{
				$data['today']['cash'] = $data['today']['cash']+$v['cash_money'];
			}
	
			//昨日充值金额
			if( date('Y-m-d',$v['cash_handletime']) == date("Y-m-d",strtotime("-1 day")) )
			{
				$data['yesterday']['cash'] = $data['today']['cash']+$v['cash_money'];
			}

			//总充值金额
			$data['all']['cash'] = $data['all']['cash']+$v['charge_money'];
		}
	}
}

//日期小计
$data['today']['total'] = $data['today']['charge']-$data['today']['cash'];
$data['yesterday']['total'] = $data['yesterday']['charge']-$data['yesterday']['cash'];
$data['all']['total'] = $data['all']['charge']-$data['all']['cash'];
?>