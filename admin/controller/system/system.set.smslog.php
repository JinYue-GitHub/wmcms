<?php
/**
* 短信模版控制器文件
*
* @version        $Id: system.set.smslog.php 2022年03月22日 09:09  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$logMod = NewModel('system.smslog');
$where['table'] = $logMod->table;
//数据条数
$total = wmsql::GetCount($where);
//数据列表
if( $orderField == '' )
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
$logList = $logMod->GetAll(GetListWhere($where));
?>