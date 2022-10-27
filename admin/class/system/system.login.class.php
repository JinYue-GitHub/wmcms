<?php
/**
 * 网站登录类文件
 *
 * @version        $Id: system.login.class.php 2018年4月11日 20:54  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemLogin
{
	private $table = '@manager_login';
	/**
	 * 写入后台登录记录
	 * @param 管理员id
	 * @param 日志状态
	 * @param 日志备注
	 */
	function SetLog($mid=0,$status=0,$remark=null)
	{
		$ua = NewClass('client');
		$uaArr = $ua->Get_Useragent();
		$userAgent = GetKey($_SERVER,'HTTP_USER_AGENT');
		$browser = str::DelHtml(GetKey($uaArr,'1'));
		if( $userAgent == '' )
		{
			$userAgent = '未知UA';
		}
		if( $browser == '' )
		{
			$browser = '未知浏览器';
		}
		$log['manager_id'] = $mid;
		$log['login_time'] = time();
		$log['login_status'] = $status;
		$log['login_ip'] = str::DelHtml(GetIp());
		$log['login_ua'] = str::DelHtml($userAgent);
		$log['login_remark'] = $remark;
		$log['login_browser'] = $browser;
		return wmsql::Insert($this->table, $log);
	}
	
	
	//获得当前ip在指定时间内的登录的错误次数
	function GetCount()
	{
		//当前时间减去错误等待的时间（分钟），所以乘以60
		$s = time() - C('config.web.admin_login_error_time')*60;
		$where['table'] = $this->table;
		$where['where']['login_status'] = array('<>','1');
		$where['where']['login_ip'] = GetIp();
		$where['where']['login_time'] = array('>',$s);
		return wmsql::GetCount($where);
	}
}
?>