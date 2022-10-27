<?php
/**
* 广告分类列表控制器文件
*
* @version        $Id: operate.ad.type.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$name = Request('name');

if( $orderField == '' )
{
	$where['order'] = 'type_id desc';
}

//获取列表条件
$where['table'] = '@ad_type';

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['type_name'] = array('like',$name);
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>