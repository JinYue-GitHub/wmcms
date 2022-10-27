<?php
/**
* 删除草稿操作处理
*
* @version        $Id: draftdel.php 2017年1月5日 21:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$did = str::Int( Request('did') , $lang['author']['draft_did_err'] );

$authorMod = NewModel('author.author');
$draftMod = NewModel('author.draft');

//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);


//设置消息的条件,查询这条数据是否存在
$draftMod->draftId = $did;
$draftMod->authorId = $author['author_id'];
$data = $draftMod->GetOne();

//如果消息存在，并且收信人为自己，就删除
if ( $data && $data['draft_author_id'] == $author['author_id'] )
{
	//删除一条数据
	$result = $draftMod->DelOne($did);
	
	if( $result )
	{
		ReturnData( $lang['author']['operate']['draftdel']['success'] , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['author']['operate']['draftdel']['fail'] , $ajax);
	}
}
//消息存在并且自己不是收信人
else if ( $data )
{
	ReturnData( $lang['author']['draft_noauthor'] , $ajax);
}
//没有数据
else
{
	ReturnData( $lang['author']['draft_no'] , $ajax);
}
?>