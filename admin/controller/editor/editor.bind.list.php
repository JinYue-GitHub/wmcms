<?php
/**
* 编辑分组成员列表控制器文件
*
* @version        $Id: editor.bind.list.php 2022年05月14日 10:46  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$groupId = Request('group_id');
$editorId = Request('editor_id');

$where = array();

$groupMod = NewModel('editor.group');
$bindMod = NewModel('editor.bind');

//分组数据
$groupArr = $groupMod->GetAll();

//判断搜索的条件
if( $groupId != '' )
{
	$where['where']['bind_group_id'] = $groupId;
}
if( $editorId != '' )
{
	$where['where']['bind_editor_id'] = $editorId;
}

//数据条数
$total = $bindMod->GetCount($where);
//查询所有分组
$bindList = $bindMod->GetAll(GetListWhere($where));
?>