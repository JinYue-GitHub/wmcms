<?php
/**
* 用户登录日值模型
*
* @version        $Id: log.model.php 2016年5月29日 10:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class LogModel
{
	public $table = '@user_login';
	public $data;
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	

	/**
	 * 插入一条数据
	 * @param 账号登录类型
	 * @param 登录状态
	 * @param 用户id
	 * @param 备注信息
	 */
	function Insert($type=1,$status=1,$uid=0,$remark=null)
	{
		//new一个客服端类。获取浏览器等信息
		$ua = NewClass('client');
		$uaArr = $ua->Get_Useragent();
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$browser = isset($uaArr['1'])?$uaArr['1']:'';
		if( $userAgent == '' )
		{
			$userAgent = '未知UA';
		}
		if( $browser == '' )
		{
			$browser = '未知浏览器';
		}
		
		//设置用户登录日志数据
		$data['login_type'] = $type;
		$data['login_status'] = $status;
		$data['login_ip'] = GetIp();
		$data['login_ua'] = $userAgent;
		$data['login_browser'] = $browser;
		$data['user_id'] = $uid;
		$data['login_time'] = time();
		$data['login_remark'] = $remark;
		return wmsql::Insert( $this->table , $data);
	}
	


	//获得当前ip在指定时间内的登录的错误次数
	function GetCount()
	{
		//当前时间减去错误等待的时间（分钟），所以乘以60
		$s = time() - C('config.web.user_login_error_time')*60;
		$where['table'] = $this->table;
		$where['where']['login_status'] = array('<>','1');
		$where['where']['login_ip'] = GetIp();
		$where['where']['login_time'] = array('>',$s);
		return wmsql::GetCount($where,'login_id');
	}
}
?>