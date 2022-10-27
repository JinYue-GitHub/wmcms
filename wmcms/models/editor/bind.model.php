<?php
/**
* 分组绑定模型
*
* @version        $Id: bind.model.php 2022年05月14日 10:39  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class BindModel
{
	//分组绑定表
	public $table = '@editor_bind';
	//编辑分组表
	public $groupTable = '@editor_group';
	//编辑表
	public $editorTable = '@editor';
	//编辑类型
	public $typeArr = array('1'=>'主编','2'=>'责编');


	/**
	 * 构造函数
	 */
	function __construct(){}
	
	/**
	 * 获取编辑类型
	 * @param 参数1，选填，类型值
	 */
	function GetType($type='')
	{
	    if( $type=='' )
	    {
	        return $this->typeArr;
	    }
	    return $this->typeArr[$type];
	}
	
	/**
	 * 根据id查找分组数据
	 * @param 参数1，必须，条件
	 */
	function GetById( $id )
	{
		$where['table'] = $this->table;
		$where['left'][$this->groupTable] = 'bind_group_id=group_id';
		$where['left'][$this->editorTable] = 'bind_editor_id=editor_id';
		$where['where']['bind_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入数据
	 * @param 参数1，必须，插入的内容
	 */
	function Insert($data)
	{
	    $data['bind_time'] = time();
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
		return wmsql::GetCount($where , 'bind_id');
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
		$where['left'][$this->groupTable] = 'bind_group_id=group_id';
		$where['left'][$this->editorTable] = 'bind_editor_id=editor_id';
		if( !isset($where['order']) || empty($where['order']) )
		{
			$where['order'] = 'bind_id desc';
		}
		return wmsql::GetAll($where);
	}
	
	/**
	 * 检查是否有存在相同的数据
	 * @param 参数1，必填，分组id
	 * @param 参数2，必填，编辑id
	 * @param 参数2，选填，id
	 */
	function CheckExist($groupId,$editorIid,$id='')
	{
		$where['table'] = $this->table;
		$where['where']['bind_group_id'] = $groupId;
		$where['where']['bind_editor_id'] = $editorIid;
		$data = wmsql::GetOne($where);
		if( !$data || ($data && $data['bind_id']==$id) )
		{
		    return false;
		}
		return true;
	}
	
	
	/**
	 * 根据用户id获得编辑的分组id
	 * @param 参数1，必填，用户id
	 * @param 参数2，选填，模块
	 */
	function GetGroupIdByUid($uid,$module='novel')
	{
		$where['table'] = $this->table;
		$where['left'][$this->editorTable] = 'bind_editor_id=editor_id';
		$where['where']['bind_module'] = $module;
		$where['where']['editor_uid'] = $uid;
		return $this->GetGroupIds(wmsql::GetAll($where));
	}
	
	/**
	 * 根据编辑id获得编辑的分组id
	 * @param 参数1，必填，编辑id
	 * @param 参数2，选填，模块
	 */
	function GetGroupIdByEid($editorId,$module='novel')
	{
		$where['table'] = $this->table;
		$where['where']['bind_module'] = $module;
		$where['where']['bind_editor_id'] = $editorId;
		return $this->GetGroupIds(wmsql::GetAll($where));
	}
	
	/**
	 * 处理分组ids
	 * @param 参数1，必填，数据列表
	 */
	function GetGroupIds($list=array())
	{
	    if( $list )
	    {
	        return implode(',',array_column($list, 'bind_group_id'));
	    }
	    else
	    {
	        return '0';
	    }
	}
}
?>