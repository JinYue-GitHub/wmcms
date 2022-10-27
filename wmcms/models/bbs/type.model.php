<?php
/**
* 论坛版块模型
*
* @version        $Id: type.model.php 2016年5月29日 12:52  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TypeModel
{
	public $bbsTable = '@bbs_bbs';
	public $typeTable = '@bbs_type';
	public $modTable = '@bbs_moderator';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	
	/**
	 * 获得一条版块分类
	 * @param 参数1，必须，版块的id
	 */
	function GetOne( $id )
	{
		$where['table'] = $this->typeTable;
		$where['where']['type_id'] = $id;
		$data = wmsql::GetOne($where);
		return $data;
	}
	
	/**
	 * 获得版块列表
	 * @param unknown $where
	 */
	function GetList($where = array(),$field='*')
	{
		$wheresql['table'] = $this->typeTable;
		$wheresql['field'] = $field;
		$wheresql['where'] = $where;
		$list = wmsql::GetAll($wheresql);
		return $list;
	}
}
?>