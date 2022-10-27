<?php
/**
* 上传预设模版静态资源控制器
*
* @version        $Id: system.templates.static.php 2016年5月16日 12:44  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');
$staticTemp4 = file_exists(WMSTATIC.$id.'/web/');
$staticTemp3 = file_exists(WMSTATIC.$id.'/m/');
$staticTemp2 = file_exists(WMSTATIC.$id.'/3g/');
$staticTemp1 = file_exists(WMSTATIC.$id.'/wap/');
?>