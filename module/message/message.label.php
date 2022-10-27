<?php
/**
* 留言标签处理类
*
* @version        $Id: message.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月5日 20:05 weimeng
*
*/
class messagelabel extends message
{
	static public $lcode;
	static public $data;

	function __construct()
	{
		tpl::labelBefore();
		
		//公共url替换
		self::PublicUrl();
	}
	
	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'新增留言'=>tpl::url('message_add'),
			'留言提交地址'=>'/wmcms/action/index.php?action=message.add',
		);
		tpl::Rep($arr);
	}
	
	
	//新增留言页的标签
	static function MessageLabel()
	{
		tpl::Rep( array('留言内容'=>C('page.content')) );
	}
}
?>