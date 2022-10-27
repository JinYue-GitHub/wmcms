<?php
/**
* 分组成员控制器文件
*
* @version        $Id: editor.bind.edit.php 2022年05月14日 10:44  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$bindMod = NewModel('editor.bind');
$groupMod = NewModel('editor.group');

//分组数据
$groupArr = $groupMod->GetAll();
$data = array();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit')
{
	$data = $bindMod->GetById($id);
}
?>