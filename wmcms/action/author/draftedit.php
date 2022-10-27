<?php
/**
* 编辑草稿操作处理
*
* @version        $Id: draftedit.php 2017年1月6日 20:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @update         2021年09月05日 10:32
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$did = str::Int( Request('did') , $lang['author']['draft_did_err'] );
$cid = str::Int( Request('contentid') , $lang['author']['draft_cid_err'] );
$module = Request('module');
$title = str::IsEmpty( Request('title') , $lang['author']['draft_title_err'] );
$content = str::IsEmpty( Post('content',null,false) , $lang['author']['draft_content_err'] );
$content = str::EditorFormat($content);
CheckShield( $content , $lang['author']['disable_draft_content'] , 'disable' );

$authorMod = NewModel('author.author');
$draftMod = NewModel('author.draft');
$novelMod = NewModel('novel.novel');
$uploadMod = NewModel('upload.upload');
//设置上传的参数
$uploadMod->module = 'author';
$uploadMod->type = 'draft';
$uploadMod->uid = $uid;

//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);

//检查模块是否正确
switch ($module)
{
	case "novel":
		$option['vid'] = str::Int( Request('vid/i') , null , 1 );
		$option['pay'] = str::Int( Request('pay/i') , null , 0);
		$option['said'] = strip_tags(Post('said',null,false));

		//小说是否存在
		$novelWhere['novel_id'] = $cid;
		$novelWhere['author_id'] = $author['author_id'];
		$novelData = $novelMod->GetOne($novelWhere);
		if( !$novelData )
		{
			ReturnData($lang['system']['content']['no'] , $ajax);
		}
		//审核中不允许写草稿
		else if( $novelData['novel_status'] == '0' )
		{
			ReturnData($lang['author']['novel_status_0'] , $ajax);
		}
		break;

	case "article":
		$option['tid'] = str::Int( Request('tid/i') , $lang['author']['article_tid_err']);
		$option['cname'] = Request('cname');
		$option['source'] = Request('source');
		$option['tags'] = Request('tags');
		$option['info'] = str::GetContentInfo($content,Request('info'));
		CheckShield( $option['info'] , $lang['author']['disable_article_info'] , 'disable' );
		$option['simg'] = Request('simg');
		break;
		
	default:
		ReturnData($lang['system']['par']['module_err'] , $ajax);
		break;
}


//公共数组
$data['draft_title'] = $title;
$data['draft_content'] = $content;
$data['draft_number'] = str::StrLen($content);
$data['draft_option'] = serialize($option);

//新增草稿
if( $did == 0 )
{
	$data['draft_author_id'] = $author['author_id'];
	$data['draft_module'] = $module;
	$data['draft_cid'] = $cid;
	$data['draft_createtime'] = time();
	
	$result = $draftMod->Insert($data);
	if( $result )
	{
		//上传文件绑定
		$uploadMod->cid = $result;
		$uploadMod->FileBind('add',$content);
		
		ReturnData( $lang['author']['operate']['draftadd']['success'] , $ajax , 200 , array('draft_id'=>$result));
	}
	else
	{
		ReturnData( $lang['author']['operate']['draftadd']['fail'] , $ajax);
	}
}
//编辑草稿
else
{
	//设置消息的条件,查询草稿是否存在
	$draftMod->draftId = $did;
	$draftMod->authorId = $author['author_id'];
	$draftData = $draftMod->GetOne();

	//如果草稿存在
	if ( $draftData )
	{
		//修改数据
		$result = $draftMod->Update($data ,$did);
		if( $result )
		{
			//上传文件绑定
			$uploadMod->cid = $did;
			$uploadMod->FileBind('edit',$content);
			
			ReturnData( $lang['author']['operate']['draftedit']['success'] , $ajax , 200);
		}
		else
		{
			ReturnData( $lang['author']['operate']['draftedit']['fail'] , $ajax);
		}
	}
	//没有数据
	else
	{
		ReturnData( $lang['author']['draft_no'] , $ajax);
	}

}
?>