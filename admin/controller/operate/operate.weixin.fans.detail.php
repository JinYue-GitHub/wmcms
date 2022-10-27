<?php
/**
* 微信素材详情控制器文件
*
* @version        $Id: operate.weixin.fans.detail.php 2019年03月12日 23:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$wxSer = NewClass('weixin_platform');
$fansMod = NewModel('operate.weixin_fans');

//接受数据
$id = Get('id');
$data = $fansMod->GetById($id);
?>