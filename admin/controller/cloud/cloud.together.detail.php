<?php
/**
* 众研需求请求处理器
*
* @version        $Id: cloud.together.php 2019年01月25日 15:03  weimeng
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
	$rs = $cloudSer->GetTogether($id);
	$data = $rs['data'];
}
?>