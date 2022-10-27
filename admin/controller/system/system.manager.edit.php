<?php
/**
* 后台管理员编辑控制器文件
*
* @version        $Id: system.admin.edit.php 2016年4月6日 11:04  weimeng
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
	$where['table'] = '@manager_manager';
	$where['where']['manager_id'] = $id;

	$data = wmsql::GetOne($where);
}

//查询所有权限
$wheresql['table'] = '@system_competence';
$compArr = wmsql::GetAll($wheresql);
?>