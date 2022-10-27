<?php
/**
* 应用厂商编辑控制器文件
*
* @version        $Id: app.firms.edit.php 2016年5月17日 17:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$firmsSer = AdminNewClass('app.firms');

//厂商的类型
$typeArr = $firmsSer->GetType();


//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@app_firms';
	$where['where']['firms_id'] = $id;

	$data = wmsql::GetOne($where);
}
?>