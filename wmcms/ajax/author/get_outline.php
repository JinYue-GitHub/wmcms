<?php
/**
* 获得小说的大纲
*
* @version        $Id: get_outline.php 2021年09月03日 19:44  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));
if( $nid == 0 )
{
	$info = $lang['author']['nid_err'];;
}
else
{
	$code = 200;
	$outlineMod = NewModel('novel.outline');
	$data = $outlineMod->GetByNid( $nid , $author['author_id']);
	if( !$data )
	{
		$code = 201;
	}
	$info = $lang['system']['operate']['success'];
}
ReturnData($info , $ajax , $code , $data);
?>