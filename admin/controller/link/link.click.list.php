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
$clickSer = AdminNewClass('link.click');

if( $orderField == '' )
{
	$where['order'] = 'click_id desc';
}

//获取列表条件
$where['table'] = '@link_click';
$where['left']['@link_link'] = 'click_lid = link_id';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>