<?php
/**
* 邮件设置控制器文件
*
* @version        $Id: system.set.email.php 2016年3月30日 14:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$emailMod = NewModel('system.email');
$emailList = $emailMod->EmailGetAll();
$tempList = $emailMod->TempGetAll();
?>