<?php
/**
* 广告编辑控制器文件
*
* @version        $Id: operate.ad.edit.php 2016年5月8日 9:50  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$adSer = AdminNewClass('operate.ad');

//所有模块分类
$adArr = $adSer->GetType();
$statusArr = $adSer->GetStatus();
$ptArr = $adSer->GetPt();
$typeIdArr  = wmsql::GetAll(array('table'=>'@ad_type'));

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@ad_ad';
	$where['where']['ad_id'] = $id;

	$data = wmsql::GetOne($where);
}
//不存在就设置默认值
else
{
	$data['ad_status'] = '1';
	$data['ad_url'] = '#';
	$data['ad_type'] = '1';
	$data['ad_time_type'] = $data['ad_price'] = '0';
	$data['ad_pt'] = '4';
	$data['ad_start_time'] = time();
	$data['ad_end_time'] = time()+3600*24*7;
	                       	
}
?>