<?php
/**
* 图集分类模型
*
* @version        $Id: type.model.php 2017年4月9日 17:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TypeModel
{
	//分类表
	public $typeTable = '@picture_type';

	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 根据id查找小说分类数据条数
	 * @param 参数1，必须，条件
	 */
	function GetById( $id )
	{
		$where['table'] = $this->typeTable.' as t';
		$where['where']['type_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 根据分类的父级id查询
	 * @param 参数1，必须，分类的父级id
	 */
	function GetByTopId( $topid )
	{
		$where['table'] = $this->typeTable;
		$where['where']['type_topid'] = $topid;
		return wmsql::GetAll($where);
	}
}
?>