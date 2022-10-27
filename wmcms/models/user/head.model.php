<?php
/**
* 用户头像模型
*
* @version        $Id: head.model.php 2016年5月29日 9:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class HeadModel
{
	public $table = '@user_head';
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	/**
	 * 获得查询的条件
	 */
	function RandOne()
	{
		$where['field'] = 'head_src';
		$where['table'] = $this->table;
		return wmsql::RandOne($where);
	}
	
	/**
	 * 获得查询的条件
	 * @param 参数1，必须，头像id
	 */
	function GetById($id)
	{
		$where['table'] = $this->table;
		$where['where']['head_id'] = $id;
		return wmsql::GetOne($where);
	}
}
?>