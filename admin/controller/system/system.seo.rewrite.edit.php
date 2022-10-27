<?php
/**
* SEO的链接地址控制器文件
*
* @version        $Id: system.seo.rewrite.edit.php 2016年4月4日 11:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@seo_urls';
	$where['where']['urls_id'] = $id;
	
	$data = wmsql::GetOne($where);
}

//所有模块分类
$moduleArr = GetModuleName();
?>