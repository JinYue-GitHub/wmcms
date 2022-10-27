<?php
/**
* 信息分类列表控制器文件
*
* @version        $Id: link.type.list.php 2016年5月13日 13:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$open = Request('open');

$typeSer = AdminNewClass('about.type');
$manager = AdminNewClass('manager');


//查询所有分类
$order['order'] = 'type_order';
$typeArr = $typeSer->GetType($order);
$typeArr = str::Tree($typeArr, 'type_topid', 'type_id');
?>