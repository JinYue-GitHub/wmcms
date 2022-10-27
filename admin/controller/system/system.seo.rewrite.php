<?php
/**
* 伪静态设置控制器文件
*
* @version        $Id: system.seo.rewrite.php 2016年4月4日 11:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$arr = Post();
$module = Request('module');
$name = Request('name');

//获取列表条件
$where['table'] = '@seo_urls';

//判断搜索的类型
if( $name != '' )
{
	$where['where']['urls_pagename'] = array('like',$name);
}		
if( $module != '' )
{
	$where['where']['urls_module'] = $module;
}
else
{
	$where['where']['urls_module'] = array('lnin',NOTMODULE);
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere());
$keyArr = wmsql::GetAll($where);

//所有模块分类
$moduleArr = GetModuleName();
$moduleArr['sitemap'] = '网站地图';
?>