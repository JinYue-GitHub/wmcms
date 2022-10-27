<?php
/**
* 编辑微信自定义菜单控制器文件
*
* @version        $Id: operate.weixin.media.edit.php 2019年03月09日 13:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$mediaMod = NewModel('operate.weixin_media');
$accountMod = NewModel('operate.weixin_account');

//接受数据
$aid = Get('aid/i');
$accountList = $accountMod->GetList();
?>