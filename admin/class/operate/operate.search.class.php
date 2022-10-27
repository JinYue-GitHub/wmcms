<?php
/**
 * 搜索的类文件
 *
 * @version        $Id: operate.search.class.php 2016年5月7日 11:00  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class OperateSearch
{
	public $table = '@search_search';
	
	/**
	 * 获取能设置预设模版的模块
	 */
	function GetModule()
	{
		$arr = GetModuleName();
	
		unset($arr['all']);
		unset($arr['user']);
		unset($arr['message']);
		unset($arr['down']);
		unset($arr['replay']);
		return $arr;
	}
	

	/**
	 * 获取搜索的类型
	 * @param 参数1，选填，获取搜索的类型
	 */
	function GetType( $k = '' )
	{
		$arr = array(
			'0'=>'搜全部',
			'1'=>'搜标题',
			'2'=>'搜作者',
			'3'=>'搜标签',
		);
	
		if( $k != '' )
		{
			return $arr[$k];
		}
		else
		{
			return $arr;
		}
	}
}
?>