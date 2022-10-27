<?php
/**
* 应用模型
*
* @version        $Id: app.model.php 2019年05月19日 13:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class AppModel
{
	//分类表
	public $typeTable = '@app_type';
	//内容表
	public $appTable = '@app_app';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$wheresql['table'] = $this->appTable;
		$wheresql['where'] = $where;
		return wmsql::GetCount($wheresql);
	}

	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->appTable.' as a';
		$wheresql['left'][$this->typeTable.' as t'] = 'a.type_id=t.type_id';
		if( is_array($where) )
		{
			$wheresql['where'] = $where;
		}
		else
		{
			$wheresql['where']['app_id'] = $where;
		}
		$data = wmsql::GetOne($wheresql);
		return $data;
	}
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetAll($where)
	{
		$wheresql['table'] = $this->appTable.' as a';
		$wheresql['field'] = 'a.*,t.*';
		$wheresql['field'] .= ',au.firms_name as au_name,pa.firms_name as pa_name';
		$wheresql['left']['@app_type as t'] = "a.type_id = t.type_id";
		$wheresql['left']['@app_firms as au'] = "a.app_aid = au.firms_id";
		$wheresql['left']['@app_firms as pa'] = "a.app_oid = pa.firms_id";
		if( isset($where['where']) )
		{
			$wheresql['where'] = $where['where'];
			$wheresql['order'] = $where['order'];
			$wheresql['limit'] = $where['limit'];
		}
		else
		{
			$wheresql['where'] = $where;
		}
		$wheresql['where']['app_status'] = "1";
		
		$data = wmsql::GetAll($wheresql);
		return $data;
	}
}
?>