<?php
/**
* 应用属性控制器文件
*
* @version        $Id: app.attr.abs.php 2016年5月16日 22:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$attrSer = AdminNewClass('app.attr');

//所有属性
$attrArr = $attrSer->GetType();

//接受post数据
$attr = Request('attr');


if( $orderField == '' )
{
	$where['order'] = 'attr_id desc';
}

//获取列表条件
$where['table'] = '@app_attr';


//判断是否搜索属性
if( $attr != '' )
{
	$where['where']['attr_type'] = $attr;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>