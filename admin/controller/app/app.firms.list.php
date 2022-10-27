<?php
/**
* 应用厂商列表控制器文件
*
* @version        $Id: app.firms.list.php 2016年5月17日 17:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$firmsSer = AdminNewClass('app.firms');

//所有属性
$typeArr = $firmsSer->GetType();

//接受post数据
$name = Request('name');
$attr = Request('attr');


//获取列表条件
$where['table'] = '@app_firms';

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['firms_name'] = array('like',$name);
}
//判断是否搜索属性
if( $attr != '' )
{
	$where['where']['firms_type'] = $attr;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
if( $orderField == '' )
{
	$where['order'] = 'firms_id desc';
}
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>