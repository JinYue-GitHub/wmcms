<?php
/**
* 筛选条件控制器文件
*
* @version        $Id: system.retrieval.edit.php 2017年6月17日 10:28  weimeng
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
$reMod = NewModel('system.retrieval');
$reSer = AdminNewClass('system.retrieval');
$manager = AdminNewClass('manager');
$typeArr = $reSer->GetType($module);
$whereArr = $reMod->GetWhereType();

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $reMod->GetOne($id);
}
else
{
	$data['retrieval_order'] = '99';
}
?>