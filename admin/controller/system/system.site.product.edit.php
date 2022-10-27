<?php
/**
* 站外产品线控制器文件
*
* @version        $Id: system.site.product.edit.php 2017年6月11日 14:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$siteMod = NewModel('system.site');
	$data = $siteMod->ProGetOne($id);
}
else
{
	$data['product_order'] = '99';
}
?>