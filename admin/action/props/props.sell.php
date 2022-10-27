<?php
/**
* 道具销售记录处理器
*
* @version        $Id: props.sell.php 2017年3月11日 20:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$curModule = 'author';
$table = '@props_sell';

//永久删除销售记录
if ( $type == 'del' )
{
	$where['sell_id'] = GetDelId();
	wmsql::Delete($table,$where);
	//写入操作记录
	SetOpLog( '删除了道具销售记录' , $curModule , 'delete' , $table , $where);
	Ajax('道具删除成功!');
}
?>