<?php
/**
* 搜索列表控制器文件
*
* @version        $Id: operate.search.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$searchSer = AdminNewClass('operate.search');

//所有模块分类
$moduleArr = $searchSer->GetModule();
$typeArr = $searchSer->GetType();

//接受post数据
$module = Request('module');
$st = Request('st');

if( $orderField == '' )
{
	$where['order'] = 'search_id desc';
}

//获取列表条件
$where['table'] = '@search_search';


//按类型查询
if( $type == 'name' )
{
	$st = str::CheckElse($st, '' , '1');
}
else if( $type == 'author')
{
	$st = str::CheckElse($st, '' , '2');
}
else if( $type == 'tag')
{
	$st = str::CheckElse($st, '' , '3');
}

//判断是否搜索标题
if( $module != '' )
{
	$where['where']['search_module'] = $module;
}
if( $st != '' && $st != '0' )
{
	$where['where']['search_type'] = $st;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>