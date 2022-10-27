<?php
/**
 * 广告的类文件
 *
 * @version        $Id: operate.operate.class.php 2016年5月11日 10:00  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class OperateOperate
{
	public $table = '@operate_operate';
	
	/**
	 * 获取互动的类型
	 * @param 参数1，选填，互动类型的键
	 */
	function GetType( $k = '' )
	{
		$arr = array(
			'ding'=>'顶互动',
			'cai'=>'踩互动',
			'score'=>'评分互动',
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
		unset($arr['author']);
		unset($arr['replay']);
		return $arr;
	}
}
?>