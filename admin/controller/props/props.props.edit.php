<?php
/**
* 道具编辑控制器文件
*
* @version        $Id: props.props.edit.php 2017年3月5日 15:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeMod = NewModel('props.type');
$propsMod = NewModel('props.props');
$propsSer = AdminNewClass('props.props');
$userConfig = GetModuleConfig('user');

//查询所有分类
$typeArr = $typeMod->GetAll();


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
	$data = $propsMod->GetById($id);
	$option = unserialize($data['props_option']);
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
	$data['props_time'] = time();
	$data['props_money'] = $data['props_gold1'] = $data['props_gold2'] = $data['props_stock'] = '0';
	$option['rec'] = $option['month'] = $option['author_exp'] = $option['fans_exp'] = $option['user_exp'] = '0';
}
?>