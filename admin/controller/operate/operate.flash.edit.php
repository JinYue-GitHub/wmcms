<?php
/**
* 幻灯片编辑控制器文件
*
* @version        $Id: operate.flash.edit.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$flashSer = AdminNewClass('operate.flash');

//查询所有分类
$typeArr = $flashSer->GetType();

//所有模块分类
$moduleArr = $flashSer->GetModule();
$statusArr = $flashSer->GetStatus();


//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@flash_flash as f';
	$where['left']['@flash_type as t'] = 't.type_id=f.type_id';
	$where['where']['flash_id'] = $id;

	$data = wmsql::GetOne($where);
}
//不存在就设置默认值
else
{
	$data['flash_status'] = '1';
	$data['flash_pid'] = '0';
	$data['flash_module'] = 'index';
	$data['flash_order'] = '10';
	$data['flash_url'] = '#';
}
?>