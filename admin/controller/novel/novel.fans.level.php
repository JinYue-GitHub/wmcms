<?php
/**
* 小说粉丝等级预设控制器文件
*
* @version        $Id: novel.fans.level.php 2017年3月30日 21:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where['table'] = '@fans_module_level';
$wgere['where']['level_module'] = 'novel';
$where['order'] = 'level_order';
$lvArr = wmsql::GetAll($where);
?>