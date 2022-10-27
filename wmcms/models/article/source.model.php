<?php
/**
* 文章模块获得文章来源、作者和编辑的模型
*
* @version        $Id: source.model.php 2017年2月11日 12:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SourceModel
{
	public $table = '@article_author';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	
	/**
	 * 获得默认的文章来源
	 */
	function GetSource()
	{
		$where['table'] = $this->table;
		$where['where']['author_type'] = 's';
		$where['where']['author_default'] = '1';
		$data = wmsql::GetOne($where);
		return $data['author_name'];
	}
}
?>