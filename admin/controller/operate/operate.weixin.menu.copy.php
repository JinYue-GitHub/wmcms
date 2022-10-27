<?php
/**
* 复制微信自定义菜单控制器文件
*
* @version        $Id: operate.weixin.menu.copy.php 2019年03月09日 14:09  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$accountMod = NewModel('operate.weixin_account');

//接受数据
$mid = Get('mid');

$data = $accountMod->GetList();
?>