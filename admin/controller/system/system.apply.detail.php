<?php
/**
* 申请数据详情控制器文件
*
* @version        $Id: system.apply.detail.php 2017年1月21日 15:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$module = Request('module');
$mt = Request('type');
$id = Request('id');
$t = Request('t');
$data = $newData = array();
if( $module == '' || $mt == '' || $t == '' || !str::Number($id) )
{
	Ajax('对不起，参数错误',300);
}
else
{
	$applyMod = NewModel('system.apply');
	$where['apply_module'] = $module;
	$where['apply_type'] = $mt;
	$where['apply_id'] = $id;
	$applyData = $applyMod->GetOne($where);
	$data = $applyMod->GetChange($applyData);
}
?>