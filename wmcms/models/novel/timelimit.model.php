<?php
/**
* 小说限时免费模型
*
* @version        $Id: timelimit.model.php 2018年8月27日 21:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TimeLimitModel
{
	public $timeTable = '@novel_timelimit';
	public $novelTable = '@novel_novel';
	public $typeTable = '@novel_type';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 插入小说上架信息
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		$data['timelimit_time'] = time();
		return wmsql::Insert($this->timeTable, $data);
	}
	
	
	/**
	 * 根据id查询
	 * @param 参数1，必须，限时免费id
	 */
	function GetById($id)
	{
		$where['timelimit_id'] = $id;
		return $this->GetOne($where);
	}
	/**
	 * 根据小说id查询
	 * @param 参数1，必须，小说id
	 */
	function GetByNid($nid)
	{
		$where['timelimit_nid'] = $nid;
		return $this->GetOne($where);
	}
	
	/**
	 * 获得小说上架信息
	 * @param 参数1，必须，小说id
	 */
	function GetOne( $where )
	{
		$wheresql['table'] = $this->timeTable;
		$wheresql['left'][$this->novelTable.' as n'] = 'timelimit_nid=novel_id';
		$wheresql['left'][$this->typeTable.' as t'] = 't.type_id=n.type_id';
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['timelimit_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->timeTable , $where);
	}
}
?>