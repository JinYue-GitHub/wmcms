<?php
/**
* 用户财务信息
*
* @version        $Id: finance.model.php 2016年12月25日 21:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class FinanceModel
{
	public $table = '@user_finance';
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 获得查询的条件
	 */
	function GetFinance($uid = '')
	{
		if( $uid == '' )
		{
			$uid = user::GetUid();
		}
		$where['table'] = $this->table;
		$where['where']['finance_user_id'] = $uid;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 插入财务资料
	 * @param 参数1，必须，插入的数据
	 */
	function InsertFinance($data)
	{
		return wmsql::Insert($this->table, $data);
	}
	
	/**
	 * 修改财务资料
	 * @param 参数1，必须，修改的数据
	 * @param 参数2，选填，修改的条件
	 */
	function UpdateFinance($data , $wheresql='')
	{
		if( $wheresql == '' )
		{
			$uid = user::GetUid();
		}
		else
		{
			$uid = $wheresql;
		}

		if( $this->GetFinance($uid) )
		{
			$where['finance_user_id'] = $uid;
			return wmsql::Update($this->table, $data , $where);
		}
		else
		{
			$data['finance_user_id'] = $uid;
			return $this->InsertFinance($data);
		}
	}
}
?>