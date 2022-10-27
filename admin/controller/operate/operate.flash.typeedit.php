<?php
/**
* 幻灯片分类控制器文件
*
* @version        $Id: operate.flash.typeedit.php 2018年8月21日 20:42  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('operate.flash');
//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'type_add';}


//如果id大于0
if ( $type == 'type_edit')
{
	$where['table'] = '@flash_type';
	$where['where']['type_id'] = $id;

	$data = wmsql::GetOne($where);
	if( $data )
	{
		//上级分类的名字
		if( $data['type_topid'] > 0 )
		{
			$where['where']['type_id'] = $data['type_topid'];
			$topData = wmsql::GetOne($where);
			$top['name'] = $topData['type_name'];
		}
		else
		{
			$top['name'] = '顶级分类';
		}
	}
}
?>