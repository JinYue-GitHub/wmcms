<?php
/**
* 用户卡号使用记录模型
*
* @version        $Id: card_log.model.php 2017年3月27日 21:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Card_LogModel
{
	public $table = '@user_card_log';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 插入卡号使用记录
	 * @param 参数1，必须，要插入的数据
	 */
	function Insert($data)
	{
		$data['log_use_time'] = time();
		if( !isset($data['log_touser_id']) )
		{
			$data['log_touser_id'] = $data['log_user_id'];
		}
		return wmsql::Insert($this->table, $data);
	}
}
?>