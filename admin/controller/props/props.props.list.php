<?php
/**
* 道具列表控制器文件
*
* @version        $Id: props.props.list.php 2017年3月6日 20:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$config = GetModuleConfig('user');
$typeSer = AdminNewClass('props.type');
$propsSer = AdminNewClass('props.props');

//接受post数据
$tid = Request('tid');
$name = Request('name');
$module = Request('module');
$tname = Request('tname');

//所有模块
$moduleArr = $typeSer->GetModule();


//获取列表条件
$where['table'] = '@props_props';
$where['left']['@props_type'] = 'type_id=props_type_id';


//判断是否搜索道具
if( $name != '' )
{
	$where['where']['props_name'] = array('like',$name);
}
else
{
	$name = '请输入道具关键字';
}
//判断是否搜索分类
if( $tid != '' )
{
	$where['where']['type_id'] = array('lin',$tid);
}
//判断是否搜索属性
if( $module != '' )
{
	$where['where']['type_module'] = $module;
}
if( $orderField == '' )
{
	$where['order'] = 'props_id desc';
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);

//所有分类
$wheresql['table'] = '@props_type';
$typeArr = wmsql::GetAll($wheresql);
?>