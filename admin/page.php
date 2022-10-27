<?php
/**
* 分页参数处理
*
* @version        $Id: page.php 2016年4月3日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//分数参数处理
//默认页数
$pageCurrent = 1;
if( Request('pageCurrent') != '' )
{
	$pageCurrent = Request('pageCurrent');
}
C('page.pageCurrent',$pageCurrent);


//默认每页条数
$pageSize = 20;
if( Request('pageSize') != '')
{
	$pageSize = Request('pageSize');
}
C('page.pageSize',$pageSize);

//默认总数据
$total = 0;



//排序字段
$orderField = '';
if( Request('orderField') != '')
{
	$orderField = Request('orderField');
}
C('page.orderField',$orderField);


//排序顺序
$orderDirection = '';
if( Request('orderDirection') != '')
{
	$orderDirection = Request('orderDirection');
}
C('page.orderDirection',$orderDirection);
?>