<?php
/**
* 内容标签分类列表
*
* @version        $Id: system.tags.type.list.php 2022年04月12日 17:01  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$name = Request('name');
list($module,$st) = explode('_', $type);
if($module == '' )
{
	$module = 'novel';
}
$tagsTypeMod = NewModel('system.tagstype');
//获取列表条件
$where['where']['type_module'] = $module;
//数据条数
$total = $tagsTypeMod->GetCount($where);
//当前页的数据
$list = $tagsTypeMod->GetAll(GetListWhere($where));
?>