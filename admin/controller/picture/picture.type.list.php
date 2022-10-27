<?php
/**
* 图集分类列表控制器文件
*
* @version        $Id: picture.type.list.php 2016年5月15日 9:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$open = Request('open');

$typeSer = AdminNewClass('picture.type');
$manager = AdminNewClass('manager');


//查询所有分类
$order['order'] = 'type_order';
$typeArr = $typeSer->GetType($order);
$typeArr = str::Tree($typeArr, 'type_topid', 'type_id');
?>