<?php
/**
* 自定义标签控制器文件
*
* @version        $Id: system.config.label.edit.php 2016年5月20日 21:58  weimeng
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
	$where['table'] = '@config_label';
	$where['where']['label_id'] = $id;
	
	$data = wmsql::GetOne($where);
}
?>