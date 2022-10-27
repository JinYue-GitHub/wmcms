<?php
/**
* 内容标签控制器
*
* @version        $Id: system.tags.type.edit.php 2022年04月12日 17:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
list($module,$type) = explode('_', $type);
if($module == '' )
{
	$module = 'novel';
}
$tagsTypeMod = NewModel('system.tagstype');

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $tagsTypeMod->GetById($id);
}
?>