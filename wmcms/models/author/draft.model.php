<?php
/**
* 草稿模型
*
* @version        $Id: draft.model.php 2016年5月28日 21:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class DraftModel
{
	public $table = '@author_draft';
	//作者id
	public $authorId;
	//草稿id
	public $draftId;
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	

	/**
	 * 插入草稿信息
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		return wmsql::Insert($this->table, $data);
	}

	/**
	 * 修改草稿内容
	 * @param 参数1，必须，修改的内容
	 */
	function Update($data , $whereArr)
	{
		if( !is_array($whereArr) )
		{
			$where['draft_id'] = $whereArr;
		}
		else
		{
			$where = $whereArr;
		}
		return wmsql::Update($this->table, $data, $where);
	}
	
	
	/**
	 * 查询一条数据
	 * @param 参数1，必须，条件
	 */
	function GetOne( $wheresql = array() )
	{
		if( empty($wheresql) )
		{
			$wheresql['draft_id'] = $this->draftId;
			$wheresql['draft_author_id'] = $this->authorId;
		}
		$where['table'] = $this->table;
		$where['where'] = $wheresql;
		$data = wmsql::GetOne($where);
		return $data;
	}
	
	/**
	 * 查询所有数据
	 * @param 参数1，必须，条件
	 */
	function GetList( $wheresql = array() , $order = '' )
	{
		$where['table'] = $this->table;
		$where['where'] = $wheresql;
		if( !empty($order) )
		{
		    $where['order'] = $order;
		}
		$data = wmsql::GetAll($where);
		return $data;
	}
	
	/**
	 * 删除一条数据
	 */
	function DelOne($id)
	{
		$where['draft_id'] = $id;
		return wmsql::Delete($this->table , $where);
	}
}
?>