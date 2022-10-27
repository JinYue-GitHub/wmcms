<?php
/**
* 微信对话消息详细信息控制器文件
*
* @version        $Id: operate.weixin.menu.detail.php 2019年03月10日 20:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$msgMod = NewModel('operate.weixin_msg');

//接受数据
$id = Get('id');
$data = $msgMod->GetById($id);
?>