<?php
/**
* 粉丝推荐记录控制器文件
*
* @version        $Id: novel.fans.ticket.php 2017年3月30日 21:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$name = Request('name');

//获取列表条件
$where['table'] = '@user_ticket_log';
$where['field'] = '@user_ticket_log.*,novel_name,user_nickname';
$where['left']['@novel_novel'] = 'log_cid=novel_id';
$where['left']['@user_user'] = 'log_user_id=user_id';
$where['where']['log_status'] = '2';
$where['where']['log_module'] = 'novel';
if( $orderField == '' )
{
	$where['order'] = 'log_id desc';
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>