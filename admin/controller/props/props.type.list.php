<?php
/**
* 道具分类分类列表控制器文件
*
* @version        $Id: author.props.type.list.php 2017年3月5日 16:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$open = Request('open');

$typeMod = NewModel('props.type');
$typeSer = AdminNewClass('props.type');
$manager = AdminNewClass('manager');

//模块
$moduleArr = $typeSer->GetModule();
//查询所有分类
$module = Get('module');
$where = array();
if( $module != '' )
{
	$where['type_module'] = $module;
}
$typeArr = $typeMod->GetAll($where);
$typeArr = str::Tree($typeArr, 'type_topid', 'type_id');
?>