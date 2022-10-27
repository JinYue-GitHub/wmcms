<?php
/**
* 书签生成
*
* @version        $Id: shortcut.php 2015年8月9日 21:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*
**/
require_once 'wmcms/inc/common.inc.php';
$shortcut = "[InternetShortcut]
URL=".DOMAIN."
IDList=[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2";
Header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$C['config']['web']['webname'].".url;");
echo $shortcut;
?>