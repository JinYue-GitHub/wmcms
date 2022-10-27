<?php
/**
* 留言模型
*
* @version        $Id: message.model.php 2016年5月27日 21:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class MessageModel
{
	public $table = '@message_message';
	public $data;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}



	/**
	 * 获得当前IP留言条数
	 */
	function GetCount()
	{
		$where['table'] = $this->table;
		$where['where']['message_ip'] = GetIp();
		$where['where']['message_time'] = array( '>' , strtotime("today"));
		
		$count = wmsql::GetCount($where);
		return $count;
	}
	
	
	/**
	 * 插入数据
	 */
	function Insert()
	{
		$data = $this->data;
		$data['message_time'] = time();
		$data['message_ip'] = GetIp();
		return wmsql::Insert( $this->table , $data);
	}
}
?>