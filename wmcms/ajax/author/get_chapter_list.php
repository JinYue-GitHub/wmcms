<?php
/**
* 获得小说的章节列表
*
* @version        $Id: get_chapter_list.php 2021年09月03日 19:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));
$vid = str::Int(Request('vid'));

if( $nid == 0 )
{
	$info = $lang['system']['par']['err'];
}
else
{
	$code = 200;
	$novelMod = NewModel('novel.novel');
	$where['novel_id'] = $nid;
	$where['author_id'] = $author['author_id'];
	$novelData = $novelMod->GetOne( $where );
	if( !$novelData )
	{
		$code = 201;
	}
	else
	{
		$chapterMod = NewModel('novel.chapter');
		$chapterWhere['chapter_nid'] = $novelData['novel_id'];
		if( $vid > 0 )
		{
			$chapterWhere['chapter_vid'] = $vid;
		}
		$data = str::ArrGroup($chapterMod->GetList( $chapterWhere ) , 'chapter_vid');
	}
	$info = $lang['system']['operate']['success'];
}

ReturnData($info , $ajax , $code , $data);
?>