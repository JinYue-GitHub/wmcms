<?php
/**
* 预设模版查找待会控制器
*
* @version        $Id: system.templates.lookup.php 2016年4月8日 16:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');

//上传的模块
$module = Get('module');
$name = Get('name');
$where = array();

if( $orderField == '' )
{
	$where['order'] = 'temp_id desc';
}

//设置条件
if( $module != '' )
{
	$where['where']['temp_module'] = $module;
}
//判断搜索的关键字
if ( $name != '' ) 
{
	$where['where']['temp_name'] = array('like',$name);
}

$data = $tempSer->GetTempList($where);
$total = $data['total'];
$tempArr = $data['data'];

//所有模块分类
$moduleArr = $tempSer->GetModuleName(GetModuleName());
//每个模块的类型
$tempTypeArr = $tempSer->GetTempType();
?>