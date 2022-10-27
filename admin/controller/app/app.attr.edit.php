<?php
/**
* 应用属性控制器文件
*
* @version        $Id: app.attr.abs.php 2016年5月16日 22:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$attrSer = AdminNewClass('app.attr');

//所有属性
$attrArr = $attrSer->GetType();


$id = Get('id');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@app_attr';
	$where['where']['attr_id'] = $id;
	$data = wmsql::GetOne($where);
}
?>