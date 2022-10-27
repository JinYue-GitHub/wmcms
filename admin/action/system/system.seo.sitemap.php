<?php
/**
* SITEMAP文件生成处理器
*
* @version        $Id: system.seo.sitemap.php 2017年2月13日 23:47  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$httpSer = NewClass('http');
$domain = DOMAIN;
$step = Request('step');
$module = Request('module');
$page = Request('page');
$tid = Request('tid');
//生成首页
if ( $type == 'sitemap' || $type == 'rss')
{
	$url = GetKey($C, 'config,seo,urls,sitemap_'.$page.'_index,url1');
	$path = GetKey($C, 'config,seo,urls,sitemap_'.$page.'_index,url2');

	$html = $httpSer->GetUrl($domain.$url);
	file::CreateFile(WMROOT.$path, $html , 1);
	Ajax($page.'地图：'.$path.'生成成功！');
}
//根据模块获取分类
else if ( $type == 'gettype' )
{
	if( $module == '' )
	{
		$data[] = array('type_id'=>0,'type_topid'=>0,'type_pid'=>0,'type_name'=>'对不起，请选择模块');
		Ajax('获取成功' , 200 , $data);
	}
	else
	{
		$wheresql['table'] = '@'.$module.'_type';
		$wheresql['field'] = 'type_id,type_topid,type_pid,type_name';
		$wheresql['order'] = 'type_order';
		$data = wmsql::GetAll($wheresql);

		Ajax('获取成功' , 200 , $data);
	}
}
//生成列表html初始化操作
else if( $type == 'list' && $step == 'init')
{
	if( $module == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	$child = Request('child');
	$tTable = $tableSer->tableArr[$module.'type']['table'];
	$tidName = $tableSer->tableArr[$module.'type']['id'];
	$pidName = $tableSer->tableArr[$module.'type']['pid'];
	$tPYName = $tableSer->tableArr[$module.'type']['pinyin'];
	if( $tPYName != '' )
	{
		$tPYField = ','.$tPYName;
	}
	$cTable = $tableSer->tableArr[$module]['table'];
	
	$wheresql['table'] = $tTable;
	$wheresql['field'] = 'type_id as tid';
	if( $tid != '0' && $child == '1')
	{
		$wheresql['where'][$tidName] = array('string',"(FIND_IN_SET({$tid},{$pidName})) or {$tidName}={$tid}");
	}
	else if( $tid != '0')
	{
		$wheresql['where'][$tidName] = $tid;
	}
	$data = wmsql::GetAll($wheresql);
	if( $data )
	{
		Ajax('初始化成功！' , 200 , $data , count($data));
	}
	else
	{
		Ajax('没有需要生成的数据!' , 300);
	}
}
//生成列表html操作
else if( $type == 'list' && $step == 'create')
{
	if( $module == '' || $tid == '')
	{
		Ajax('参数错误!' , 300);
	}
	else
	{
		$par = array('tid'=>$tid,'module'=>$module,'type'=>'rss');
		$url = tpl::url( 'sitemap_rss_list' , $par , 1);
		$htmlPath = tpl::url( 'sitemap_rss_list' , $par , 2);

		$html = $httpSer->GetUrl($domain.$url);
		file::CreateFile(WMROOT.$htmlPath, $html , 1);
		Ajax(GetModuleName($module).'，分类ID：'.$tid.'。'.$htmlPath.'生成成功！');
	}
}
?>