<?php
/**
* seo编辑控制器文件
*
* @version        $Id: system.seo.keys.edit.php 2016年4月3日 17:43  weimeng
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
	$where['table'] = '@seo_keys';
	$where['where']['keys_id'] = $id;
	
	$data = wmsql::GetOne($where);
}

//所有模块分类
$moduleArr = GetModuleName();
?>