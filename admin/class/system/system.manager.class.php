<?php
/**
 * 管理员类文件
 *
 * @version        $Id: system.manager.class.php 2016年4月6日 20:45  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemManager
{
	/**
	 * 管理员账号检测
	 * @param 参数1，必须，账号状态
	 */
	function GetAdminStatus($status)
	{
		switch ($status)
		{
			case "1":
				$status = '<span style="color:green">正在使用</span>';
				break;
			default:
				$status = '<span style="color:red">已经禁用</span>';
				break;
		}
		return $status;
	}
}
?>