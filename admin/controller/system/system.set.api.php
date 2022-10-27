<?php
/**
* 接口设置控制器文件
*
* @version        $Id: system.set.api.php 2016年3月25日 16:45  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//查询接口数据
$where['table'] = '@api_api as a';
$where['order'] = 'type_order,api_order';
$where['left'] = array('@api_type as t'=>'t.type_id=a.type_id');
$apiArr = wmsql::GetAll($where);

foreach ($apiArr as $k=>$v)
{
	$newArr[$v['type_name']][] = $v;
}
$apiArr = $newArr;
?>