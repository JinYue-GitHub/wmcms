<?php
/**
* 编辑分组控制器文件
*
* @version        $Id: editor.group.list.php 2022年05月13日 09:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$module = Request('module');
$groupMod = NewModel('editor.group');
$where = array();
//所有模块分类
$moduleArr = GetModuleName('novel',false);

//判断搜索的类型
if( $module != '' )
{
	$where['where']['keys_module'] = $module;
}
//数据条数
$total = $groupMod->GetCount($where);
//查询所有分组
$groupArr = $groupMod->GetAll(GetListWhere($where));

?>