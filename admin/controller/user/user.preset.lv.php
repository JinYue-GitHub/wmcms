<?php
/**
* 用户等级预设控制器文件
*
* @version        $Id: user.preset.lv.php 2016年5月5日 21:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where['table'] = '@user_level';
$where['order'] = 'level_order';
$lvArr = wmsql::GetAll($where);

$userConfig = GetModuleConfig('user');
?>