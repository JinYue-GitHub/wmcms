<?php
/**
* 获得小说的本章的作者有话说
*
* @version        $Id: get_chapter_said.php 2021年09月03日 19:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));
$cid = str::Int(Request('cid'));
if( $nid == 0 || $cid == 0 )
{
	$info = $lang['author']['nid_err'];;
}
else
{
	$code = 200;
	$saidMod = NewModel('novel.said');
	$data = $saidMod->GetByNid( $nid , $cid , $author['author_id']);
	if( !$data )
	{
		$code = 201;
	}
	$info = $lang['system']['operate']['success'];
}
ReturnData($info , $ajax , $code , $data);
?>