<?php
/**
* 错误请求处理器
*
* @version        $Id: cloud.err.php 2017年2月25日 13:23  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');

//获得我的错误列表
if ( $type == 'getlist' )
{
	$page = str::Page(Request('page'));
	$pageCount = str::Int(Request('pagecount' , 20));
	$rs = $cloudSer->GetErrlogList($page , $pageCount);
	Ajax('请求成功！',200,$rs);
}
?>