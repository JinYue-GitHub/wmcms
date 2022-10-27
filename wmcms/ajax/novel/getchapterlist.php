<?php
/**
* 获得小说的分类的信息
*
* @version        $Id: gettype.php 2016年12月31日 22:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$vid = str::Int(Request('vid'));
$nid = str::Int(Request('nid'));
$page = str::Page(Request('page'));
$pageCount = str::Int(Request('pagecount'));
$order = Request('order','chapter_order');
$orderType = Request('order_type','asc');


$code = 500;
$data = array();
$chapterMod = NewModel('novel.chapter');

if( $nid == 0 )
{
	$info = $lang['novel']['nid_err'];
}
else if( $vid == 0 )
{
	$info = $lang['novel']['vid_err'];
}
else
{
	$code = 200;
	$where = tpl::GetWhere('order='.$order.' '.$orderType.';chapter_vid='.$vid.';chapter_nid='.$nid.';page='.$page.';number='.$pageCount);
	$data = $chapterMod->GetList( $where );
	if( !$data )
	{
		$code = 201;
	}
	else
	{
		foreach ($data as $k=>$v)
		{
			$data[$k]['chapter_status_text'] = $lang['novel']['chapter_'.$v['chapter_status']];
			$data[$k]['chapter_time'] = date("Y-m-d H:i:s",$v['chapter_time']);
		}
	}
	$info = $lang['system']['operate']['success'];
}

ReturnData($info , $ajax , $code , $data);
?>