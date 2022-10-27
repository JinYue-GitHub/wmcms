<?php
/**
* 站内站点列表控制器文件
*
* @version        $Id: system.site.site.php 2017年6月12日 15:15  weimeng
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
	$where['order'] = 'site_order';
}
//判断搜索的类型
if( $name != '' )
{
	$where['where']['site_title'] = array('like',$name);
}
else
{
	$name = '站点名字';
}
//数据条数
$total = $siteMod->SiteGetCount($where);
//当前页的数据
$data = $siteMod->SiteGetAll(array_merge($where , GetListWhere($where)));
?>