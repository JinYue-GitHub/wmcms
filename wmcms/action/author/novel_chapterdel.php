<?php
/**
* 删除章节操作处理
*
* @version        $Id: novel_chapterdel.php 2017年1月8日 20:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$cid = str::Int( Request('cid') , $lang['author']['novel_cid_err'] );

$authorMod = NewModel('author.author');
$chapterMod = NewModel('novel.chapter');

//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);


//设置消息的条件,查询这条数据是否存在
$data = $chapterMod->GetById($cid);
//数据不存在
if( !$data )
{
	ReturnData( $lang['author']['chapter_no'] , $ajax);
}
//作者不是自己
else if( $data && $data['author_id'] != $author['author_id'] )
{
	ReturnData( $lang['author']['author_no'] , $ajax);
}
else
{
	//删除一条数据
	$result = $chapterMod->DeleteById($cid);
	
	if( $result )
	{
		ReturnData( $lang['author']['operate']['novel_chapter_del']['success'] , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['author']['operate']['novel_chapter_del']['fail'] , $ajax);
	}
}
?>