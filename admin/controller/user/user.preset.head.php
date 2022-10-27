<?php
/**
* 用户头像预设控制器文件
*
* @version        $Id: user.preset.head.php 2016年5月5日 15:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/

$where['table'] = '@user_head';
$headArr = wmsql::GetAll($where);
?>