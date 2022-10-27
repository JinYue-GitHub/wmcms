<?php
/**
* 作者模块收入的模型
*
* @version        $Id: module_income.model.php 2017年3月18日 22:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Module_IncomeModel
{
	private $incomeTable = '@author_module_income';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	

	/**
	 * 插入作者模块的收入记录信息
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，作者id
	 * @param 参数3，必须，内容id
	 */
	function Insert( $module , $aid , $cid )
	{
		$data['income_module'] = $module;
		$data['income_author_id'] = $aid;
		$data['income_cid'] = $cid;
		return wmsql::Insert($this->incomeTable, $data);
	}
	
	
	/**
	 * 更新作者模块内容收入
	 * @param 参数1，选填，模块id
	 * @param 参数2，选填，用户id
	 * @param 参数3，必须，模块内容id
	 * @param 参数4，必须，收入的金币1
	 * @param 参数4，必须，收入的金币2
	 */
	function SetIncome( $module , $aid , $cid , $gold1 , $gold2)
	{
		if( $gold1 == '0' && $gold2 == '0' )
		{
			return false;
		}
		else
		{
			//获得粉丝的消费数据
			$incomeData = $this->GetIncome( $module , $aid , $cid );

			$config = GetModuleConfig('author');
			$exp = 0;
			$gold1Now = $incomeData['income_gold1_now'] + $gold1;
			$gold2Now = $incomeData['income_gold2_now'] + $gold2;

			
			//当前消费的金币1数量是否大于了票增加设定值
			if( $gold1Now >= $config['income_gold1'] )
			{
				//获得倍数
				$multiple = floor($gold1Now / $config['income_gold1']);
				//去除倍数后剩余的当前消费金额
				$gold1Now = $gold1Now - ( $config['income_gold1'] * $multiple);
				$exp += $multiple;
			}
			//当前消费的金币2数量是否大于了票增加设定值
			if( $gold2Now >= $config['income_gold2'] )
			{
				//获得倍数
				$multiple = floor($gold2Now / $config['income_gold2']);
				//去除倍数后剩余的当前消费金额
				$gold2Now = $gold2Now - ( $config['income_gold2'] * $multiple);
				$exp += $multiple;
			}
			
			//修改消费记录增加值
			$where['income_id'] = $incomeData['income_id'];
			$data['income_gold1_now'] = $gold1Now;
			$data['income_gold2_now'] = $gold2Now;
			$data['income_gold1'] = array('+' , $gold1);
			$data['income_gold2'] = array('+' , $gold2);
			wmsql::Update( $this->incomeTable , $data , $where);
			
			//消费满足后
			if($exp > 0 )
			{
				$expMod = NewModel('author.exp');
				$expMod->Update( $module , $aid , $exp );
			}
		}
	}
	
	
	/**
	 * 获得作者当前模块的收入信息
	 * @param 参数1，必须，模块id
	 * @param 参数2，必须，用户id
	 * @param 参数3，必须，内容id
	 */
	function GetIncome( $module , $aid , $cid )
	{
		if( $aid > 0 && $cid > 0 )
		{
			$where['table'] = $this->incomeTable;
			$where['where']['income_module'] = $module;
			$where['where']['income_author_id'] = $aid;
			$where['where']['income_cid'] = $cid;
			$data = wmsql::GetOne($where);
			if( !$data )
			{
				$this->Insert($module, $aid, $cid);
				$data = wmsql::GetOne($where);
			}
			return $data;
		}
	}
}
?>