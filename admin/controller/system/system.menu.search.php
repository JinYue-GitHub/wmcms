<?php
/**
* 目录搜索控制器文件
*
* @version        $Id: system.menu.search.php 2016年5月15日 17:45  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$menuSer = AdminNewClass('system.menu');

//接受参数
$key = Request('key');

//查询所有符合条件的目录
$where['table'] = '@system_menu';
$where['where']['menu_status'] = 1;
$where['where']['menu_title'] = array('like',$key);
$where['where']['menu_file'] = array('<>','');
$where['where']['menu_group'] = array('<>','2');
$menuArr = wmsql::GetAll($where);
?>