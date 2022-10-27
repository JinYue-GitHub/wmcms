<?php
/**
* 编辑模型
*
* @version        $Id: editor.model.php 2022年05月13日 10:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class EditorModel
{
	//编辑表
	public $table = '@editor';
	//用户表
	public $userTable = '@user_user';

	//状态
	public $statusArr = array('1'=>'启用','0'=>'禁用');

	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	
	/**
	 * 获取状态数据
	 * @param 参数1，选填，状态值
	 */
	function GetStatus($status='')
	{
	    if( $status=='' )
	    {
	        return $this->statusArr;
	    }
	    return $this->statusArr[$status];
	}
	
	/**
	 * 根据id查找分组数据
	 * @param 参数1，必须，条件
	 */
	function GetById( $id )
	{
		$where['table'] = $this->table;
		$where['left'][$this->userTable] = 'editor_uid=user_id';
		$where['where']['editor_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 根据用户id查找分组数据
	 * @param 参数1，必须，用户id
	 */
	function GetByUid( $uid )
	{
		$where['table'] = $this->table;
		$where['left'][$this->userTable] = 'editor_uid=user_id';
		$where['where']['editor_uid'] = $uid;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入数据
	 * @param 参数1，必须，插入的内容
	 */
	function Insert($data)
	{
	    $data['editor_time'] = time();
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
		return wmsql::GetCount($where , 'editor_id');
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
		$where['left'][$this->userTable] = 'editor_uid=user_id';
		if( !isset($where['order']) || empty($where['order']) )
		{
			$where['order'] = 'editor_id desc';
		}
		return wmsql::GetAll($where);
	}
	
	/**
	 * 检查是否有存在相同的数据
	 * @param 参数1，必填，用户id
	 * @param 参数2，选填，id
	 */
	function CheckExist($uid,$id='')
	{
		$where['table'] = $this->table;
		$where['where']['editor_uid'] = $uid;
		$data = wmsql::GetOne($where);
		if( !$data || ($data && $data['editor_id']==$id) )
		{
		    return false;
		}
		return true;
	}
}
?>