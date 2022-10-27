<?php
/**
* 微信素材详情控制器文件
*
* @version        $Id: operate.weixin.media.detail.php 2019年03月10日 20:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$wxSer = NewClass('weixin_platform');
$mediaMod = NewModel('operate.weixin_media');

//接受数据
$id = Get('id');
$data = $mediaMod->GetById($id);
?>