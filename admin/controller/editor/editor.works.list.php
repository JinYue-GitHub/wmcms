<?php
/**
* 作品关联控制器文件
*
* @version        $Id: editor.works.list.php 2022年05月13日 14:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cid = Request('cid');
$groupId = Request('group_id');
$editorId = Request('editor_id');

$where = array();

$groupMod = NewModel('editor.group');
$worksMod = NewModel('editor.works');

//分组数据
$groupArr = $groupMod->GetAll();

//判断搜索的条件
if( $groupId != '' )
{
	$where['where']['works_group_id'] = $groupId;
}
if( $cid != '' )
{
	$where['where']['works_cid'] = $cid;
}
if( $editorId != '' )
{
	$where['where']['works_editor_id'] = $editorId;
}

//数据条数
$total = $worksMod->GetCount($where);
//查询所有分组
$worksList = $worksMod->GetAll(GetListWhere($where));
?>