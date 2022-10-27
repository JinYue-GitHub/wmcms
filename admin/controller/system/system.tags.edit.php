<?php
/**
* 内容标签控制器
*
* @version        $Id: system.tags.edit.php 2022年04月01日 10:28  weimeng
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
$tagsMod = NewModel('system.tags');
$authorRec = $tagsMod->GetAuthorRec();

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $tagsMod->GetById($id);
}

//分类数据
$tagsTypeMod = NewModel('system.tagstype');
$typeWhere['where']['type_module'] = $module;
$typeData = $tagsTypeMod->GetAll(GetListWhere($typeWhere));
?>