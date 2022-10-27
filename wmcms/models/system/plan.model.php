<?php
/**
* 静态计划模型
*
* @version        $Id: plan.model.php 2019年02月27日 14:08  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class PlanModel
{
	private $planTable = '@seo_html_plan';
	private $planIdName = 'plan_id';
	
	function __construct( $data = '' )
	{
	}
	
	/**
	 * 插入计划
	 * @param 参数1，必须，需要插入的数据
	 */
	function Insert($data)
	{
		$data['plan_addtime'] = time();
		$data['plan_lasttime'] = time();
		return wmsql::Insert($this->planTable, $data);
	}
	
	/**
	 * 修改最后运行的时间
	 * @param 参数1，必须，修改的条件
	 */
	function UpLastTime($wheresql)
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		else
		{
			$where[$this->planIdName] = $wheresql;
		}
		$data['plan_lasttime'] = time();
		return wmsql::Update($this->planTable, $data, $where);
	}
	
	/**
	 * 根据id获得数据
	 * @param 参数1，必须，id
	 */
	function GetById($id)
	{
		$where[$this->planIdName] = $id;
		return $this->GetOne($where);
	}
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($wheresql)
	{
		$where['table'] = $this->planTable;
		$where['where'] = $wheresql;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 获得数据总条数
	 * @param 参数1，选填，查询条件
	 */
	function GetCount($wheresql = array())
	{
		$where['table'] = $this->planTable;
		$where['where'] = $wheresql;
		return wmsql::GetCount($where,$this->planIdName);
	}
	
	/**
	 * 获得列表数据
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，内容id
	 */
	function GetList($wheresql = array())
	{
		$where['table'] = $this->planTable;
		if( isset($wheresql['where']) )
		{
			$where['where'] = $wheresql['where'];
		}
		if( isset($wheresql['limit']) )
		{
			$where['limit'] = $wheresql['limit'];
		}
		if( isset($wheresql['order']) )
		{
			$where['order'] = $wheresql['order'];
		}
		$list = wmsql::GetAll($where);
		return $list;
	}
	
	/**
	 * 删除一条记录
	 * @param 参数1，必须，删除条件
	 */
	function Delete($wheresql)
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		else
		{
			$where[$this->planIdName] = $wheresql;
		}
		return wmsql::Delete($this->planTable,$where);
	}
}
?>