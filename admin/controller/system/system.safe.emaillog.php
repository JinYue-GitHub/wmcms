<?php
/**
* 邮件发送记录
*
* @version        $Id: system.safe.emaillog.php 2017年6月27日 15:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$emailMod = NewModel('system.email');
//数据条数
$total = $emailMod->LogGetCount();
//当前页的数据
$where = GetListWhere();
if( $where['order'] == '')
{
	$where['order'] = 'log_id desc';
}
//接受post数据
$logReceive = Request('log_receive');
//判断搜索的类型
if( $logReceive != '' )
{
	$where['where']['log_receive'] = $logReceive;
}
else
{
	$logReceive = '';
}
$logArr = $emailMod->LogGetAll($where);
?>