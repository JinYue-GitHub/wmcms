<?php
/**
* 友链列表控制器文件
*
* @version        $Id: link.link.list.php 2016年4月18日 9:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/

if( $orderField == '' )
{
	$where['order'] = 'link_lastintime desc';
}

//获取列表条件
$where['table'] = '@link_link as l';
$where['field'] = '*,FORMAT(((link_outsum-link_insum)/link_outsum*100),2) AS owed';
$where['where'] = 'link_outsum > link_insum';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@link_type as t'] = 'l.type_id=t.type_id';
$dataArr = wmsql::GetAll($where);
?>