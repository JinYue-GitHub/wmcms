<?php
/**
* 财务申请模型
*
* @version        $Id: finance_apply.model.php 2018年9月8日 11:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Finance_ApplyModel
{
	private $applyTable = '@finance_apply';
	
	/**
	 * 构造函数
	 */
	function __construct(){}


	/**
	 * 根据id获得申请数据
	 * @param 参数1，必须，记录id
	 */
	function GetById($id)
	{
		$where['apply_id'] = $id;
		return $this->GetOne($where);
	}
	
	/**
	 * 根据月份获得申请数据
	 * @param 参数1，必须，月份
	 * @param 参数2，选填，来源模块
	 * @param 参数3，选填，内容id
	 */
	function GetByMonth($month,$module='',$cid=0)
	{
		$where['apply_month'] = $month;
		if( $module != '' )
		{
			$where['apply_module'] = $module;
		}
		if( $cid > 0 )
		{
			$where['apply_cid'] = $cid;
		}
		return $this->GetOne($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($wheresql,$order='')
	{
		$where['table'] = $this->applyTable;
		$where['where'] = $wheresql;
		$where['order'] = 'apply_id desc';
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入的数据
	 * @param 参数1，必须， 需要插入的数据
	 */
	function Insert($data)
	{
		$data['apply_time'] = time();
		return wmsql::Insert($this->applyTable, $data);
	}
	
	
	/**
	 * 修改数据
	 * @param 参数1，必须， 需要修改的数据
	 * @param 参数2，必须， 修改的数据id
	 */
	function Update($data,$id)
	{
		$where['apply_id'] = $id;
		return wmsql::Update($this->applyTable, $data, $where);
	}
}
?>