<?php
/**
* 站外站点列表控制器文件
*
* @version        $Id: system.site.product.php 2017年6月11日 22:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$siteMod = NewModel('system.site');
$siteSer = AdminNewClass('system.site');

//接受post数据
$name = Request('name');

//获取列表条件
if( $orderField == '' )
{
	$where['order'] = 'product_order';
}
//判断搜索的类型
if( $name != '' )
{
	$where['where']['product_title'] = array('like',$name);
}
else
{
	$name = '站点名字';
}
//数据条数
$total = $siteMod->ProGetCount($where);
//当前页的数据
$data = $siteMod->ProGetAll(array_merge($where , GetListWhere($where)));
?>