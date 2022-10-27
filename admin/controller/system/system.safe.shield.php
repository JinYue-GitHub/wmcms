<?php
/**
* 敏感词库设置控制器文件
*
* @version        $Id: system.safe.shield.php 2020年5月28日 10:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
//词库获取
$shield = file::GetFile(WMCONFIG.'key.shield.txt');
$disable = file::GetFile(WMCONFIG.'key.disable.txt');
?>