<?php
/**
* 错误页面记录控制器
*
* @version        $Id: system.seo.errpage.php 2017年6月8日 20:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//获取列表条件
$where['table'] = '@seo_errpage';
//数据条数
$total = wmsql::GetCount($where);
//当前页的数据
if( $orderField == '')
{
	$where['order'] = 'errpage_id desc';
}
$errpageArr = wmsql::GetAll(GetListWhere($where));
?>