<?php
/**
* 复制微信自动回复控制器文件
*
* @version        $Id: operate.weixin.autoreply.copy.php 2019年03月09日 14:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$accountMod = NewModel('operate.weixin_account');

//接受数据
$rid = Get('rid');

$data = $accountMod->GetList();
?>