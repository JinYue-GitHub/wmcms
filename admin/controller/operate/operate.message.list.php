<?php
/**
* 留言列表控制器文件
*
* @version        $Id: operate.message.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if( $orderField == '' )
{
	$where['order'] = 'message_id desc';
}

//获取列表条件
$where['table'] = '@message_message';


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>