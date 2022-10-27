<?php
/**
* 道具分类模块模型
*
* @version        $Id: type.model.php 2017年3月5日 16:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TypeModel
{
	public $table = '@props_type';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	
	/**
	 * 获得全部分类数据
	 * @param 参数1，选填，查询条件
	 */
	function GetAll($wheresql = '')
	{
		$where['table'] = $this->table;
		if( is_array($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条分类数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($wheresql)
	{
		$where['table'] = $this->table;
		$where['where'] = $wheresql;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 根据id获得分类数据
	 * @param 参数1，必须，分类id
	 */
	function GetById($id)
	{
		$where['type_id'] = $id;
		return $this->GetOne($where);
	}
	
	/**
	 * 根据id获得分类数据
	 * @param 参数1，必须，分类模块
	 * @param 参数1，必须，分类名字
	 * @param 参数3，选填，分类id
	 */
	function GetByName($module , $name , $id = '')
	{
		if($id > 0 )
		{
			$where['type_id'] = array('!=' , $id);
		}
		$where['type_name'] = $name;
		$where['type_module'] = $module;
		return $this->GetOne($where);
	}
}
?>