<?php
/**
* 上传预设模版控制器
*
* @version        $Id: system.templates.edit.php 2016年4月7日 14:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');
if( $type == '' ){$type = 'add';}

	
//接受数据
$id = Get('id');
//上传的模块
$module = Get('module');
//上传的页面
$page = Get('page');
//点击的文本框id和name
$name = Get('name');
$tid = Get('tid');
//是否重新指定字段前缀
$reName = Get('rename');

//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@system_templates';
	$where['where']['temp_id'] = $id;
	
	$data = wmsql::GetOne($where);
	if( $data )
	{
		$module = $data['temp_module'];
		$page = $data['temp_type'];
	}
}
else
{
	$data['temp_address'] = '22';
}

//所有模块分类
$moduleArr = $tempSer->GetModuleName(GetModuleName());
//每个模块的类型
$tempTypeArr = $tempSer->GetTempType();
//模版路径类型
$tempAddressArr = $tempSer->GetTempAddress();
?>