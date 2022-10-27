<?php
/**
* 内容标签模块模型
*
* @version        $Id: tags.model.php 2016年12月31日 12:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TagsTypeModel
{
	public $table = '@system_tags_type';
	
	function __construct( $data = '' ){}

	/**
	 * 根据id获得数据
	 * @param 参数1，必须，标签id
	 */
	function GetById($id)
	{
		$where['table'] = $this->table;
		$where['where']['type_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 插入数据
	 * @param 参数1，必须，插入的内容
	 */
	function Insert($data)
	{
    	return wmsql::Insert($this->table,$data);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，修改的条件
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
		return wmsql::GetCount($where , 'type_id');
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
		if( !isset($where['order']) )
		{
			$where['order'] = 'type_order asc';
		}
		return wmsql::GetAll($where);
	}
}
?>