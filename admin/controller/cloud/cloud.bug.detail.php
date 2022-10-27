<?php
/**
* bug请求处理器
*
* @version        $Id: cloud.bug.php 2017年2月22日 22:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');
$id = str::Int(Request('id'));
$data = array();
if( $id > 0)
{
	$rs = $cloudSer->GetMessage($id);
	$data = $rs['data'];
}
?>