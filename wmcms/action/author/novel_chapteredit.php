<?php
/**
* 新增/编辑章节操作处理
*
* @version        $Id: novel_chapteredit.php 2017年1月8日 12:50 weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$did = str::Int( Request('did') );
$cid = str::Int( Request('cid') );
$nid = str::Int( Request('contentid') , $lang['author']['chapter_contentId_err'] );
$title = str::IsEmpty( Request('title') , $lang['author']['chapter_title_err'] );
CheckShield( $title , $lang['author']['disable_draft_title'] , 'disable' );
$content = str::IsEmpty( str::EditorFormat(Request('content','',false)) , $lang['author']['chapter_content_err'] );
$said = strip_tags(Request('said'));
CheckShield( $content , $lang['author']['disable_draft_content'] , 'disable' );
$vid = str::Int( Request('vid') , null , 1 );
$pay = str::Int( Request('pay') , null , 0);


$authorMod = NewModel('author.author');
$novelMod = NewModel('novel.novel');
$chapterMod = NewModel('novel.chapter');
$draftMod = NewModel('author.draft');
$applyMod = NewModel('system.apply' , 'author');
$saidMod = NewModel('novel.said');

//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);


//小说是否存在
$novelWhere['novel_id'] = $nid;
$novelWhere['author_id'] = $author['author_id'];
$novelData = $novelMod->GetOne($novelWhere);
if( !$novelData )
{
	ReturnData($lang['system']['content']['no'] , $ajax);
}
//如果小说没有允许出售就只能为公共章节
if( $novelData['novel_sell'] == '0' )
{
	$pay = '0';
}


//检查章节名字是否重复检查
if ( $chapterMod->CheckName($title , $nid , $cid) > 0 )
{
	ReturnData($lang['author']['chapter_name_exist'] , $ajax);
}

//公共数据设置
$data['chapter_nid'] = $nid;
$data['chapter_number'] = str::StrLen($content);
$data['chapter_name'] = $title;
$data['chapter_istxt'] = $novelConfig['data_type'];
$data['chapter_vid'] = $vid;
$data['chapter_ispay'] = $pay;
//检查序列化格式
if( !str::IsSerialized(array_merge($data,array('content'=>$content))) )
{
	ReturnData($lang['author']['chapter_content_format'] , $ajax);
}

//新增章节
if( $cid == 0 )
{
	$status = $authorConfig['author_novel_createchapter'];
	//草稿是否存在
	if( $did > 0)
	{
		$draftWhere['draft_id'] = $did;
		$draftWhere['draft_author_id'] = $author['author_id'];
		$draftWhere['draft_module'] = 'novel';
		$draftData = $draftMod->GetOne($draftWhere);
		//如果存在就删除草稿
		if( $draftData )
		{
			$draftMod->DelOne($did);
		}
	}
	
	//如果章节顺序为0就查找最新章节的顺序
	$order = $chapterMod->GetChapterOrder($nid);

	$data['chapter_status'] = $status;
	$data['chapter_cid'] = $cid;//内容id
	$data['chapter_order'] = $order;
	$data['chapter_time'] = time();
	
	//插入章节
	$type = 'add';
	$lastId = $chapterMod->Insert($data);
	
	$wordNumber = 0;
}
//编辑章节
else
{
	$lastId = $cid;
	$type = 'edit';
	$where['chapter_id'] = $cid;
	$status = $authorConfig['author_novel_editchapter'];
	$chapterData = $chapterMod->GetOne($where);
	//章节不存在
	if( !$chapterData )
	{
		ReturnData($lang['author']['chapter_no'] , $ajax);
	}
	else
	{
		//是自动审核才直接修改数据
		if( $status == 1 )
		{
			$wordNumber = $chapterData['chapter_number'];
			$chapterMod->Update($data , $cid);
		}
		else
		{
			$applyLast = $applyMod->GetLastData('novel_editchapter' , $uid, $lastId);
			if( $applyLast && $applyLast['apply_status'] == 0 )
			{
				ReturnData($lang['author']['chapter_apply_no'] , $ajax);
			}
		}
	}
}
//同时更新作者说
$saidMod->UpdateByCid($nid,$lastId,$said);


//插入小说编辑申请记录
InserApply($status,$data,$content,$lastId,$type);
//如果是不需要审核就执行
if( $status == 1 )
{
	//创建小说文章内容
	$chapterMod->CreateChapter( $type , $nid , $lastId , $content);
	//更新小说字数
	$novelMod->UpWordNumber($nid , $novelData['novel_wordnumber'] , $wordNumber , $data['chapter_number']);
	//更新小说的最新章节信息
	$novelMod->UpNewChapter($novelData , $lastId,$title);
	
	//创建HTML
	$htmlMod = NewModel('system.html' , array('module'=>'novel'));
	$htmlMod->CreateContentHtml($lastId);
}

ReturnData($lang['author']['operate']['novel_chapter_'.$type.'_'.$status]['success'] , $ajax , 200 , array('chapter_id'=>$lastId,'word_number'=>$data['chapter_number']));


//插入章节编辑申请记录
function InserApply($status,$chapterData,$content,$id,$type)
{
	global $lang,$applyMod,$author,$uid,$novelData;

	$data['apply_module'] = 'author';
	$data['apply_type'] = 'novel_editchapter';
	$data['apply_status'] = $status;
	$data['apply_uid'] = $uid;
	$data['apply_cid'] = $id;
	$data['apply_pid'] = $novelData['novel_id'];
	$data['apply_remark'] = $lang['author']['apply_remark_'.$status];
	//需要保存的数据
	$chapterData['content'] = $content;
	$chapterData['type'] = $type;
	$data['apply_option'] = $chapterData;
	//如果是自动审核就插入时间
	if( $data['apply_status'] == '1' )
	{
		$data['apply_updatetime'] = time();
	}
	$applyMod->Insert($data , 0);
}
?>