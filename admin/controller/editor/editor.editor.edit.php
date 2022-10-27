<?php
/**
* 编辑控制器文件
*
* @version        $Id: editor.editor.edit.php 2022年05月13日 11:08  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$editorMod = NewModel('editor.editor');
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
	$data = $editorMod->GetById($id);
}
?>