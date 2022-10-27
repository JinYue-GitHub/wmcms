<?php
/**
* 获得文章的分类的信息
*
* @version        $Id: gettype.php 2017年2月11日 13:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$tid = Request('tid');
$child = str::Int(Request('child'));
$cid = str::Int(Request('cid'));
$code = 500;
$data = array();
$typeMod = NewModel('article.type');
$fieldMod = NewModel('system.field');

//根据id查询文章
if( str::Number($tid) )
{
	C('res.field' , serialize($fieldMod->GetFiled('article', $tid , $cid)));
	$code = 200;
	if( $child == 0 )
	{
		$data = $typeMod->GetById( $tid );
	}
	else
	{
		$data = $typeMod->GetByTopId( $tid );
	}
	if( !$data )
	{
		$code = 201;
	}
	$info = $lang['system']['operate']['success'];
}
else
{
	$info = $lang['article']['tid_err'];
}
//根

ReturnData($info , $ajax , $code , $data);
?>