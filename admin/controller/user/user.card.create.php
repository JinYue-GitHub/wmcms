<?php
/**
* 用户卡号生成控制器文件
*
* @version        $Id: user.card.edit.php 2017年3月27日 17:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');

$cardMod = NewModel('user.card');
$cardArr = $cardMod->GetCardType();
?>