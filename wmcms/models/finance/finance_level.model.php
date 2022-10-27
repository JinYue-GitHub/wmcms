<?php
/**
* 财务充值等级模型
*
* @version        $Id: financelevel.model.php 2017年4月2日 11:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_LevelModel
{
	private $table = '@finance_level';
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	/**
	 * 获得最后一个符合条件的充值等级
	 * @param 参数1，必须，充值的金额
	 */
	function GetLastLevel($money)
	{
		$where['table'] = $this->table;
		$where['where']['level_money'] = array('<=',$money);
		$where['order'] = 'level_money desc';
		$where['limit'] = '1';
		return wmsql::GetOne($where);
	}

	/**
	 * 根据充值金额获得需要奖励的值
	 * @param 参数1，必须，充值金额
	 */
	function GetChargeReward($money)
	{
		$data = $this->GetLastLevel($money);
		if(!$data)
		{
			return 0;
		}
		else
		{
			return $data['level_reward_gold2'];
		}
	}
	
}
?>