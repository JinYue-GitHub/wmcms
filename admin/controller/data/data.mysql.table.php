<?php
/**
* 数据库库列表控制器
*
* @version        $Id: data.mysql.table.php 2016年5月13日 11:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tableArr = wmsql::Query('SHOW TABLE STATUS');
?>