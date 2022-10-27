<?php
/**
* 文章分类列表控制器文件
*
* @version        $Id: article.type.list.php 2016年4月12日 11:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$open = Request('open');

$typeSer = AdminNewClass('article.type');
$manager = AdminNewClass('manager');


//查询所有分类
$typeArr = $typeSer->GetType();
$typeArr = str::Tree($typeArr, 'type_topid', 'type_id');
?>