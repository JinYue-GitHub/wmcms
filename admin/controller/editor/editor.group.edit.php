<?php
/**
* 编辑分组控制器文件
*
* @version        $Id: editor.group.edit.php 2022年05月13日 10:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$groupMod = NewModel('editor.group');
//所有模块分类
$moduleArr = GetModuleName('novel',false);
$data = array();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit')
{
	$data = $groupMod->GetById($id);
}
?>