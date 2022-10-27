<?php
/**
* 专题分类列表控制器文件
*
* @version        $Id: operate.zt.typelist.php 2018年8月13日 21:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$open = Request('open');

$typeSer = AdminNewClass('operate.zt');
$manager = AdminNewClass('manager');


//查询所有分类
$order['order'] = 'type_order';
$typeArr = $typeSer->GetType($order);
$typeArr = str::Tree($typeArr, 'type_topid', 'type_id');
?>