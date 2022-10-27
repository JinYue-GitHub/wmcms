<?php
/**
* 推荐票模型
*
* @version        $Id: ticket_log.model.php 2017年3月17日 17:02  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Ticket_LogModel
{
	public $table = '@user_ticket_log';
	
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	

	/**
	 * 插入推荐票使用获得记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['log_time'] = time();
		return wmsql::Insert($this->table, $data);
	}
}
?>