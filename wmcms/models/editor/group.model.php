<?php
/**
* 编辑分组模型
*
* @version        $Id: group.model.php 2022年05月13日 09:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class GroupModel
{
	//分组表
	public $table = '@editor_group';

	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 根据id查找分组数据
	 * @param 参数1，必须，条件
	 */
	function GetById( $id )
	{
		$where['table'] = $this->table;
		$where['where']['group_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入数据
	 * @param 参数1，必须，插入的内容
	 */
	function Insert($data)
	{
	    $data['group_time'] = time();
    	return wmsql::Insert($this->table,$data);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，修改的条件
	 * @param 参数3，选填，所属模块
	 */
	function Update($data,$where)
	{
	    return wmsql::Update($this->table,$data,$where);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，删除的条件
	 */
	function Del($where)
	{
	    return wmsql::Delete($this->table,$where);
	}
	
	/**
	 * 根据获得全部相关数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		//数据条数
		return wmsql::GetCount($where , 'group_id');
	}
	
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，选填，查询条件
	 */
	function GetAll($wheresql='')
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		$where['table'] = $this->table;
		if( !isset($where['order']) || empty($where['order']) )
		{
			$where['order'] = 'group_order desc';
		}
		return wmsql::GetAll($where);
	}
}
?>