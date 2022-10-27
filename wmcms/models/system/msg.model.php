<?php
/**
* 系统站内消息模型
*
* @version        $Id: msg.model.php 2020年05月29日 10:32  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class MsgModel
{
	public $table = '@system_msg_temp';
	public $userMsgTable = '@user_msg';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	/**
	 * 根据id获取
	 * @param 参数1，必须，查询条件
	 */
	function GetById($id)
	{
		$where['table'] = $this->table;
		$where['where']['temp_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 根据键名获取
	 * @param 参数1，必须，查询条件
	 */
	function GetByKey($key)
	{
		$where['table'] = $this->table;
		$where['where']['temp_key'] = $key;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入一条数据
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		return wmsql::Insert( $this->table , $data);
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
	
	/**
	 * 发送系统模版消息
	 * @param 参数1，必须，消息模版标识
	 * @param 参数2，必须，用户id
	 * @param 参数3，选填，替换标签
	 */
	function SendTempMsg($key,$uid,$data=array())
	{
		$tempData = $this->GetByKey($key);
		if( $tempData )
		{
			$content = $tempData['temp_content'];
			if( $data )
			{
				$content = tpl::Rep($data,$content);
			}
			$msgData['msg_tuid'] = $uid;
			$msgData['msg_content'] = $content;
			$msgData['msg_time'] = time();
			return wmsql::Insert( $this->userMsgTable , $msgData);
		}
		else
		{
			return false;
		}
	}
}
?>