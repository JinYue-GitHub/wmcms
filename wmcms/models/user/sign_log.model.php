<?php
/**
* 用户签到记录模型
*
* @version        $Id: sign_log.model.php 2021年01月13日 14:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Sign_LogModel
{
	public $table = '@user_sign_log';
	//用户id
	public $userId;
	//数据
	public $data;
	
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	

	/**
	 * 查询本月签到
	 * @param 参数1，选填，条件
	 */
	function GetMonth($uid)
	{
		$where = array('log_user_id'=>$uid);
		$dataSer = NewClass('data');
		$dataSer->showDate = true;
		$dataSer->showWeek = true;
		return $dataSer->GetList( 'user_sign_log' , array('log_user_id') , 'log_time' ,'month',$where,$uid);
	}
	
	
	/**
	 * 插入一条数据
	 * @param 参数1，选填，插入的数据
	 */
	function Insert($data=array())
	{
		$data['log_date'] = date('Y-m-d');
		$data['log_time'] = time();
		return wmsql::Insert( $this->table , $data);
	}
}
?>