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
//上传的页面
$page = Get('page');
//点击的文本框id和name
$name = Get('name');
$tid = Get('tid');
$key = Post('key');
//是否重新指定字段前缀
$reName = Get('rename');

//设置条件
$where['where']['temp_module'] = $module;
$where['where']['temp_type'] = $page;
//判断搜索的关键字
if ( $key != '' ) 
{
	$where['where']['temp_name'] = array('like',$key);
}
$data = $tempSer->GetTempList($where);
$total = $data['total'];
$tempArr = $data['data'];


//所有模块分类
$moduleArr = $tempSer->GetModuleName(GetModuleName());
//每个模块的类型
$tempTypeArr = $tempSer->GetTempType();
?>