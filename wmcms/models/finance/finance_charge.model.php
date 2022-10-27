<?php
/**
* 财务充值模型
*
* @version        $Id: finance_charge.model.php 2017年4月3日 11:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_ChargeModel
{
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	function GetType($type='')
	{ 
	    if( empty($type) )
	    {
        	$api = C('config.api');
			foreach ($api as $key=>$val)
			{
				if($val['api_type'] == '3' || $val['api_type'] == '6' || $val['api_type'] == '7')
				{
					$arr[$key] = $val['api_title'];
				}
			}
			$arr['card'] = '卡密支付';
			return $arr;
	    }
	    else
	    {
    		if( $type == 'card' )
    		{
    			return '卡密支付';
    		}
    		else
    		{
    			return C('config.api.'.$type.'.api_title');
    		}
	    }
	}
	
	
	/**
	 * 插入充值
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，充值的金额
	 * @param 参数3，必须，充值的方式,charge_card卡号充值
	 * @param 参数3，选填，附加参数
	 */
	function Charge($uid , $money , $type , $option = '')
	{
		$userMod = NewModel('user.user');
		$config = C('',null,'financeConfig');
		$logData['log_module'] = 'finance';
		
		//插入用户卡号充值收入资金变更记录
		$logData['module'] = 'finance';
		$logData['type'] = $type;
		$logData['remark'] = '充值收入！';
		//如果是卡号充值
		if( $type == 'charge_card' )
		{
			$logData['remark'] = '卡密充值收入！';
		}
		$userMod->CapitalChange($uid , $logData , 0 , $money*$config['rmb_to_gold2']);
		
		//如果是卡号充值并且有奖励
		if( $type == 'charge_card' && $option['card_give'] > 0 )
		{
			$logData['remark'] = '卡密充值赠送！';
			$logData['type'] = 'charge_cardreward';
			$userMod->CapitalChange($uid , $logData , 0 , $option['card_give']);
		}
		
		
		//如果在活动开启，并且在活动期间
		if( $config['activity_open'] == 1 && $config['activity_starttime'] <time() && $config['activity_endtime'] > time() )
		{
			$levelMod = NewModel('finance.finance_level');
			$levelData = $levelMod->GetLastLevel($money);
			if( $levelData && $levelData['level_reward_gold2'] >0 )
			{
				$logData['type'] = 'charge_activity';
				$logData['remark'] = '活动期间充值赠送！';
				$userMod->CapitalChange($uid , $logData , 0 , $levelData['level_reward_gold2']);
			}
		}
		
		//首充奖励大于0，并且用户没有首充过。
		if( $config['recharge_reward_gold2'] > 0 && user::GetIsCharge() == 0)
		{
			$logData['type'] = 'charge_first';
			$logData['remark'] = '首充赠送！';
			$userMod->CapitalChange($uid , $logData , 0 , $config['recharge_reward_gold2']);
			//修改用户充值状态
			$userMod->SaveCharge($uid);
		}
		return true;
	}
}
?>