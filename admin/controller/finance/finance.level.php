<?php
/**
* 小说粉丝等级预设控制器文件
*
* @version        $Id: finance.level.php 2017年4月2日 10:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');

$where['table'] = '@finance_level';
$where['order'] = 'level_money';
$lvArr = wmsql::GetAll($where);
?>