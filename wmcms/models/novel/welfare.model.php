<?php
/**
* 小说福利设置模型
*
* @version        $Id: welfare.model.php 2018年9月2日 11:26  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class WelfareModel
{
	public $welfareTable = '@novel_welfare';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 插入小说福利
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		$data = $this->SerData($data);
		return wmsql::Insert($this->welfareTable, $data);
	}

	
	/**
	 * 修改小说福利
	 * @param 参数1，必须，修改的数据
	 * @param 参数2，必须，条件
	 */
	function Update( $data ,$where)
	{
		$data = $this->SerData($data);
		return wmsql::Update($this->welfareTable, $data,$where);
	}
	
	
	/**
	 * 根据id查询
	 * @param 参数1，必须，福利id
	 */
	function GetById($id)
	{
		$where['welfare_id'] = $id;
		return $this->GetOne($where);
	}
	/**
	 * 根据小说id查询
	 * @param 参数1，必须，小说id
	 */
	function GetByNid($nid)
	{
		$where['welfare_nid'] = $nid;
		$data = $this->GetOne($where);
		if( $data )
		{
			return $this->UnSerData($data);
		}
		else
		{
			return $data;
		}
	}
	
	/**
	 * 获得小说福利
	 * @param 参数1，必须，查询
	 */
	function GetOne( $where )
	{
		$wheresql['table'] = $this->welfareTable;
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}

	/**
	 * 序列化数据
	 * @param 参数1，必须，需要范序列化的数据
	 */
	function SerData($data)
	{
		$data['welfare_type'] = serialize(GetKey($data,'welfare_type'));
		$data['welfare_finish'] = serialize(GetKey($data,'welfare_finish'));
		$data['welfare_update'] = serialize(GetKey($data,'welfare_update'));
		$data['welfare_full'] = serialize(GetKey($data,'welfare_full'));
		return $data;
	}
	/**
	 * 反序列化数据
	 * @param 参数1，必须，需要范序列化的数据
	 */
	function UnSerData($data)
	{
		$data['welfare_type'] = unserialize($data['welfare_type']);
		$data['welfare_finish'] = unserialize($data['welfare_finish']);
		$data['welfare_update'] = unserialize($data['welfare_update']);
		$data['welfare_full'] = unserialize($data['welfare_full']);
		if( $data['welfare_number'] == '0.00' )
		{
			$data['welfare_number'] = '';
		}
		return $data;
	}

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['welfare_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->welfareTable , $where);
	}
}
?>