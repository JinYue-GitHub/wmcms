<?php
/**
* 内容标签列表
*
* @version        $Id: system.tags.list.php 2022年04月01日 10:26  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$name = Request('name');
$authorRec = Request('author_rec');
$typeId = Request('type_id');
list($module,$st) = explode('_', $type);
if($module == '' )
{
	$module = 'novel';
}
$tagsMod = NewModel('system.tags');
//获取列表条件
$where['where']['tags_module'] = $module;
//判断搜索的类型
if( $name != '' )
{
	$where['where']['tags_name'] = array('like',$name);
}
//判断是否作者推荐
if( $authorRec != '' )
{
	$where['where']['tags_author_rec'] = $authorRec;
}
//判断分类
if( $typeId != '' )
{
	$where['where']['tags_type_id'] = $typeId;
}
//获取列表条件
if( $orderField == '' )
{
	$where['order'] = 'tags_data desc,tags_id desc';
}
//数据条数
$total = $tagsMod->GetCount($where);
//当前页的数据
$list = $tagsMod->GetAll(GetListWhere($where));

//分类数据
$tagsTypeMod = NewModel('system.tagstype');
$typeWhere['where']['type_module'] = $module;
$typeData = $tagsTypeMod->GetAll(GetListWhere($typeWhere));
?>