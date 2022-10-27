<?php
/**
 * 评论的类文件
 *
 * @version        $Id: operate.replay.class.php 2016年5月6日 15：37  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class OperateReplay
{
	public $table = '@replay_replay';
	
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
		unset($arr['author']);
		return $arr;
	}
}
?>