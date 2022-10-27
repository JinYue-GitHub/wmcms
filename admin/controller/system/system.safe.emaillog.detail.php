<?php
/**
* 邮件发送日志记录详情
*
* @version        $Id: system.safe.emaillog.detail.php 2017年6月27日 16:08  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');

//如果id大于0
if ( str::Number($id) )
{
	$emailMod = NewModel('system.email');
	$data = $emailMod->LogGetOne($id);
}
?>