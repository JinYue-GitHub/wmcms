<?php
/**
* 广告分类编辑控制器文件
*
* @version        $Id: operate.ad.type.edit.php 2016年5月8日 14:16  weimeng
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
	$where['table'] = '@ad_type';
	$where['where']['type_id'] = $id;

	$data = wmsql::GetOne($where);
}
?>