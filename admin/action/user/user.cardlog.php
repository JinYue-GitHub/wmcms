<?php
/**
* 卡号使用记录处理器
*
* @version        $Id: user.cardlog.php 2017年4月3日 19:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@user_card_log';

//删除数据
if ( $type == 'del')
{
	$where['log_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了卡号使用记录！' , 'user' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
	
	Ajax('卡号使用记录批量删除成功!');
}
//清空数据
else if ( $type == 'clear')
{
	//写入操作记录
	SetOpLog( '清空了所有卡号使用记录！' , 'user' , 'delete' , $table);
	wmsql::Delete($table);
	Ajax('卡号使用记录全部清空成功！');
}
?>