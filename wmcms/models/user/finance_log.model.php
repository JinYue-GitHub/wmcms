<?php
/**
* 用户财务日志
*
* @version        $Id: finance_log.model.php 2017年3月17日 15:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_LogModel
{
	public $table = '@user_finance_log';
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 插入财务资料
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['log_time'] = time();
		return wmsql::Insert($this->table, $data);
	}
	

	/**
	 * 获得模块的所有收入日志
	 * @param 参数1，必须，模块
	 * @param 参数2，选填，是否加载别名
	 * @param 参数3，选填，是否是别名模式
	 */
	function GetLogType($module='' , $cname = '' , $isCname = 0)
	{
		$type = '';
		$typeArr = $this->GetTypeName($module , $cname , $isCname);
		if( empty($module) )
		{
		    return $typeArr;
		}
		else
		{
    		$endV = end($typeArr);
    		foreach ($typeArr as $k=>$v)
    		{
    			$type .= $k;
    			if( $endV != $v )
    			{
    				$type .=',';
    			}
    		}
    		return $type;
		}
	}
	
	
	/**
	 * 获得日志类型的名字
	 * @param 参数1，选填，模块
	 * @param 参数2，选填，日志类型
	 * @param 参数3，选填，是否是别名模式
	 */
	function GetTypeName($module='' , $type='' , $isCname = 0)
	{
		$apiTypeArr = C('config.api');
		foreach ($apiTypeArr as $key=>$val)
		{
			if( in_array($val['api_type'],array(3,6,7)) )
			{
				$chargeArr['charge_'.$key] = $val['api_title'];
			}
		}
		$chargeArr['charge_card'] = '卡密充值';
		$chargeArr['charge_first'] = '首充奖励';
		$chargeArr['charge_reward'] = '充值赠送';

		$arr = array(
			'novel'=>array(
				'sub'=>array(
					'novel_number_income'=>'单章出售',
					'novel_month_income'=>'包月出售',
					'novel_all_income'=>'全本出售',
					'novel_number_buy'=>'单章购买',
					'novel_month_buy'=>'包月购买',
					'novel_all_buy'=>'全本购买',
				),
				'props'=>array(
					'props_income'=>'道具出售',
					'props_buy'=>'道具购买',
				),
				'reward'=>array(
					'reward_income'=>'粉丝打赏',
					'reward_consume'=>'打赏支出',
				),
			),
			'finance'=>array(
				'cash'=>array(
					'cash_apply'=>'提现申请',
					'cash_refuse'=>'提现拒绝返还',
				),
				'charge'=>$chargeArr,
				'reward'=>array(
					'charge_activity'=>'充值活动赠送',
					'charge_first'=>'首充赠送',
					'charge_cardreward'=>'卡密充值赠送',
				),
			),
			'system'=>array(
				'reward'=>'系统奖励',
				'finance_apply'=>'财务结算',
			),
			'user'=>array(
				'login'=>'系统奖励',
			),
			'bbs'=>array(
				'post'=>'发帖奖励',
			),
			'replay'=>array(
				'add'=>'评论奖励',
			),
		);
		if( !empty($module) && isset($arr[$module]) )
		{
			$newArr = $arr;
			if( $isCname == '0' )
			{
				foreach ($arr[$module] as $key=>$val)
				{
					if( is_array($val) )
					{
						foreach ($val as $k=>$v)
						{
							$newArr[$module][$k] = $v;
						}
					}
					else
					{
						$newArr[$module][$key] = $val;
					}
				}
			}
			if( $type == '' )
			{
				if( isset($newArr[$module]) )
				{
					return $newArr[$module];
				}
				else
				{
					return $module.'-'.$type;
				}
			}
			else if( isset($newArr[$module][$type]) )
			{
				return $newArr[$module][$type];
			}
		}
    	else if( empty($module) )
    	{
			foreach ($arr as $key=>$val)
			{
				if( is_array($val) )
				{
					foreach ($val as $k=>$v)
					{
				    	if( is_array($v) )
        				{
        					foreach ($v as $k1=>$v1)
        					{
        						$newArr[$k1] = $v1;
        					}
        				}
        				else
        				{
					    	$newArr[$k] = $v;
        				}
					}
				}
				else
				{
					$newArr[$key] = $val;
				}
			}
			return $newArr;
    	}
	}
}
?>