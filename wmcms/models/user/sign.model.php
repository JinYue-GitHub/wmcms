<?php
/**
* 用户签到模型
*
* @version        $Id: sign.model.php 2016年5月28日 22:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SignModel
{
	public $table = '@user_sign';
	//用户id
	public $userId;
	//数据
	public $data;
	
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	
	/**
	 * 获得查询的条件
	 */
	function GetWhere()
	{
		$where['user_id'] = $this->userId;
		return $where;
	}
	
	
	/**
	 * 查询一条数据
	 * @param 参数1，必须，条件
	 */
	function GetOne($id='')
	{
		$where['table'] = $this->table;
		$where['where'] = $this->GetWhere();
		$data = wmsql::GetOne($where);
		return $data;
	}
	


	/**
	 * 查询最新的一条数据
	 * @param 参数1，选填，条件
	 */
	function GetLastOne($id='')
	{
		$where['table'] = $this->table;
		$where['where'] = $this->GetWhere();
		$where['order'] = 'sign_id desc';
		$data = wmsql::GetOne($where);
		return $data;
	}

	/**
	 * 查询数量
	 * @param 参数1，选填，条件
	 */
	function GetCount($where=array())
	{
		$wheresql['table'] = $this->table;
		$wheresql['where'] = $where;
		return wmsql::GetCount($wheresql,'sign_id');
	}
	
	
	/**
	 * 插入一条数据
	 * @param 参数1，选填，插入的数据
	 */
	function Insert($data=array())
	{
		if( !isset($data['sign_sum']) )
		{
			$data['sign_sum'] = 1;
		}
		if( !isset($data['sign_con']) )
		{
			$data['sign_con'] = 1;
		}
		$data['sign_time'] = time();
		$data['user_id'] = $this->userId;
		return wmsql::Insert( $this->table , $data);
	}
	
	
	/**
	 * 修改签到数据
	 * @param 参数1，选填，条件
	 */
	function Save($data=array())
	{
		if( empty($data) )
		{
			$data = $this->data;
		}
		return wmsql::Update($this->table, $data, array('user_id'=>$this->userId));
	}
}
?>