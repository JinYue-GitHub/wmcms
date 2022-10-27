<?php
/**
 * 安全类文件
 *
 * @version        $Id: system.safe.class.php 2016年4月6日 17:21  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemSafe
{
	/**
	 * 管理员账号状态检测
	 * @param 参数1，必须，账号状态
	 */
	function GetAdminStatus($status)
	{
		switch ($status)
		{
			case "1":
				$status = '<span style="color:green">登录成功</span>';
				break;
			case "2":
				$status = '<span style="color:red">密码错误</span>';
				break;
			default:
				$status = '<span style="color:red">登录失败</span>';
				break;
		}
		return $status;
	}
	
	

	/**
	 * 获取操作的模块
	 * @param 参数1，必填，模块的名字。
	 */
	function GetModuleName( $key )
	{
		$arr = GetModuleName();
		$arr['system'] = '系统功能';
		$arr['finance'] = '财务模块';
		unset($arr['all']);
	
		return $arr[$key];
	}

	
	/**
	 * 获取操作模块的类型
	 * @param 参数1，必须，操作的类型
	 */
	function GetOperaName( $type )
	{
		switch ( $type )
		{
			case "insert":
				$type = '新增数据';
				break;
				
			case "update":
				$type = '修改数据';
				break;
				
			case "delete":
				$type = '删除数据';
				break;
		}
		
		return $type;
	}
	
	
	
	/**
	 * 根据传入的操作条件和数据返回出合并后的数组
	 */
	function GetOpData( $where , $data )
	{
		//判断当前的数据是多条修改还是单条修改
		foreach ($data as $key=>$val)
		{
			//多条数据修改
			if ( is_array($val) )
			{
				$isMore = true;
			}
			//单条数据修改
			else
			{
				$isMore = false;
			}
		}
		
		
		if ( $isMore )
		{
			//判断当前的数据是多条修改还是单条修改
			$i = 1;
			foreach ($where as $key=>$val)
			{
				foreach ($val as $k=>$v)
				{
					$arr[$i][0] = array($k,$v);
				}
				//多条数据修改
				$i++;
			}
				
			$i = 1;
			foreach ($data as $key=>$val)
			{
				foreach ($val as $k=>$v)
				{
					$arr[$i][1][] = array($k,$v);
				}
				//多条数据修改
				$i++;
			}
		}
		else
		{
			foreach ($where as $key=>$val)
			{
				$arr[0][0] = array($key,$val);
			}
			foreach ($data as $key=>$val)
			{
				$arr[0][1][] = array($key,$val);
			}
		}
		
		return $arr;
	}
}
?>