<?php
/**
* 用户处罚模型
*
* @version        $Id: punish.model.php 2020年05月28日 15:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class PunishModel
{
	public $table = '@user_punish';
	public $userTable = '@user_user';
	public $typeArr = ['login'=>'禁止登陆','talk'=>'禁止发言'];
	public $statusArr = ['0'=>'已失效','1'=>'执行中'];
	
	//构造函数
	function __construct(){}
	
	/**
	 * 获得状态
	 * @param 参数1，选填，状态
	 */
	function GetPunishStatus($status='')
	{
		if( !empty($status) && isset($this->statusArr[$status]) )
		{
			return $this->statusArr[$status];
		}
		else
		{
			return $this->statusArr;
		}
	}
	/**
	 * 获得类型
	 * @param 参数1，选填，类型
	 */
	function GetPunishType($type='')
	{
		if( !empty($type) && isset($this->typeArr[$type]) )
		{
			return $this->typeArr[$type];
		}
		else
		{
			return $this->typeArr;
		}
	}
	
	/**
	 * 检查过期
	 */
	function CheckExp()
	{
		//将所有过期的设置为已失效
		$upData['punish_status'] = 0;
		$upWhere['punish_status'] = 1;
		$upWhere['punish_endtime'] = array('<',time());
		return wmsql::Update($this->table,$upData,$upWhere);
	}
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，处罚用户
	 * @param 参数2，必须，处罚类型
	 */
	function GetOne($uid,$type)
	{
		//将所有过期的设置为已失效
		$upData['punish_status'] = 0;
		$upWhere['punish_status'] = 1;
		$upWhere['punish_endtime'] = array('<',time());
		wmsql::Update($this->table,$upData,$upWhere);
		
		$where['table'] = $this->table;
		$where['where']['punish_status'] = 1;
		$where['where']['punish_uid'] = $uid;
		$where['where']['punish_type'] = $type;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入一条数据
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		//如果是禁止登陆就更新用户表的封禁信息
		if( $data['punish_type'] == 'login' )
		{
			$userData['user_display'] = 2;
			$userData['user_displaytime'] = $data['punish_endtime'];
			wmsql::Update($this->userTable,$userData,array('user_id'=>$data['punish_uid']));
		}
		$data['punish_starttime'] = time();
		$data['punish_createtime'] = time();
		return wmsql::Insert( $this->table , $data);
	}
	
	/**
	 * 解除处罚
	 * @param 参数1，必须，处罚用户
	 * @param 参数2，必须，处罚类型
	 */
	function UnPunish($uid,$type)
	{
		//如果是禁止登陆就更新用户表的封禁信息
		if( $type == 'login' )
		{
			$userData['user_display'] = 1;
			$userData['user_displaytime'] = 0;
			wmsql::Update($this->userTable,$userData,array('user_id'=>$uid));
		}
		//将所有过期的设置为已失效
		$data['punish_status'] = 0;
		$where['punish_uid'] = $uid;
		$where['punish_type'] = $type;
		return $this->Update($data,$where);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的数据
	 * @param 参数2，必须，修改的条件
	 */
	function Update($data,$where)
	{
		return wmsql::Update($this->table,$data,$where);
	}
	
	/**
	 * 删除封禁记录
	 * @param 参数1，必须，用户id
	 */
	function Delete($where)
	{
		return wmsql::Delete($this->table , $where);
	}
}
?>