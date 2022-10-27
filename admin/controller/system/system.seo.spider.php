<?php
/**
* 蜘蛛爬行记录控制器
*
* @version        $Id: system.seo.spider.php 2017年6月8日 20:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//获取列表条件
$where['table'] = '@seo_spider';
//数据条数
$total = wmsql::GetCount($where);
//当前页的数据
if( $orderField == '')
{
	$where['order'] = 'spider_id desc';
}
$spiderArr = wmsql::GetAll(GetListWhere($where));
?>