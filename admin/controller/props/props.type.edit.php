<?php
/**
* 道具分类编辑控制器文件
*
* @version        $Id: author.props.type.edit.php 2017年3月5日 16:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('props.type');
$typeMod = NewModel('props.type');
//查询所有分类
$typeArr = $typeMod->GetAll();
//模块
$moduleArr = $typeSer->GetModule();


$id = Get('id');
if( $id > 0 )
{
	$type = 'edit';
}
else
{
	$type = 'add';
}

//如果id大于0
if ( $type == 'edit')
{
	$data = $typeMod->GetById($id);
	if( $data )
	{
		//上级分类的名字
		if( $data['type_topid'] > 0 )
		{
			$topData = $typeMod->GetById($data['type_topid']);
			$top['name'] = $topData['type_name'];
		}
		else
		{
			$top['name'] = '顶级分类';
		}
	}
}
else
{
	$data['type_order'] = '0';
}
?>