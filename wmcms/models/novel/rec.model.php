<?php
/**
* 小说推荐模型
*
* @version        $Id: rec.model.php 2019年01月16日 13:47  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class RecModel
{
	public $recTable = '@novel_rec';
	public $novelTable = '@novel_novel';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['rec_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->recTable , $where);
	}
}
?>