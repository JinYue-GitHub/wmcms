<?php
/**
* 粉丝阅读日志记录控制器文件
*
* @version        $Id: novel.fans.read.php 2017年7月12日 16:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$config = GetModuleConfig('user');
$readMod = NewModel('user.read');

//数据条数
$where['where']['read_module'] = 'novel';
$total = $readMod->GetLogCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = $readMod->GetLogList($where);
?>