<?php
/**
* 小说大纲模型
*
* @version        $Id: outline.model.php 2021年09月03日 19:47  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class OutlineModel
{
	//大纲表
	public $outlineTable = '@novel_outline';
	//小说表
	public $novelTable = '@novel_novel';

	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	/**
	 * 删除数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['outline_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->outlineTable , $where);
	}
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->outlineTable;
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 根据小说和作者id获得大纲
	 * @param 参数1，必须，小说id
	 * @param 参数2，选填，作者id
	 */
	function GetByNid($nid,$aid=0)
	{
		if( $aid > 0 )
		{
			$where['table'] = $this->novelTable;
			$where['where']['novel_id'] = $nid;
			$where['where']['author_id'] = $aid;
			if( wmsql::GetCount($where) == 0 )
			{
				return array();
			}
		}
		$wheresql['novel_id'] = $nid;
		return $this->GetOne($wheresql);
	}
}
?>