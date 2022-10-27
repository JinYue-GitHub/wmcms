<?php
/**
* 检索分类控制器
*
* @version        $Id: system.retrieval.type.php 2017年6月16日 21:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
list($module,$st) = explode('_', $type);
if($module == '' )
{
	$module = 'novel';
}
$where['where']['type_module'] = $module;

$reMod = NewModel('system.retrieval');
$typeArr = $reMod->GetType($where);
?>