<?php
/**
* 用户惩罚控制器文件
*
* @version        $Id: user.punish.punish.php 2020年05月28日 15:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$punishMod = NewModel('user.punish');
$typeArr = $punishMod->GetPunishType();

$uid = Request('uid');
$st = Request('st');
//封禁结束时间,默认3天
$endTime = date('Y-m-d H:i:s',time()+3600*24*3);
?>