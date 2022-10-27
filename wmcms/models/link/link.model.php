<?php
/**
* 友链模块模型
*
* @version        $Id: link.model.php 2016年5月27日 21:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class LinkModel
{
	public $table = '@link_link';
	public $where;
	public $data;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}



	/**
	 * 获得内容的条数
	 */
	function CheckData()
	{
		$where['table'] = $this->table;
		$where['where'] = $this->where;
		
		$count = wmsql::GetCount($where);
		return $count;
	}
	
	
	/**
	 * 插入数据
	 */
	function Insert()
	{
		return wmsql::Insert($this->table, $this->data);
	}
}
?>