<?php
/**
* 新增bug请求处理器
*
* @version        $Id: cloud.bug.add.php 2017年2月25日 11:23  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');
$rs = $cloudSer->GetMessageType();
$typeData = $rs['data'];
?>