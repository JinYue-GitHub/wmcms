<?php
/**
* 广告列表控制器文件
*
* @version        $Id: operate.ad.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$adSer = AdminNewClass('operate.ad');

//接受post数据
$ptArr = $adSer->GetPt();

$name = Request('name');
$ad_pt = Request('ad_pt');

if( $orderField == '' )
{
	$where['order'] = 'ad_id desc';
}

//获取列表条件
$where['table'] = '@ad_ad';

//判断是否搜索标题
if( $ad_pt != '' )
{
	$where['where']['ad_pt'] = $ad_pt;
}
//判断是否搜索标题
if( $name != '' )
{
	$where['where']['ad_name'] = array('like',$name);
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>