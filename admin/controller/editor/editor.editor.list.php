<?php
/**
* 编辑控制器文件
*
* @version        $Id: editor.editor.list.php 2022年05月13日 11:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$uid = Request('uid');
$where = array();

$editorMod = NewModel('editor.editor');

//判断搜索的类型
if( $uid != '' )
{
	$where['where']['editor_uid'] = $uid;
}
//数据条数
$total = $editorMod->GetCount($where);
//查询所有分组
$editorList = $editorMod->GetAll(GetListWhere($where));
?>