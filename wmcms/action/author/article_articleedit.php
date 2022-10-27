<?php
/**
* 新增/编辑文章操作处理
*
* @version        $Id: article_edit.php 2016年12月25日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$authorMod = NewModel('author.author');
$draftMod = NewModel('author.draft');
$tagsMod = NewModel('system.tags');
$applyMod = NewModel('system.apply' , 'author');
$uploadMod = NewModel('upload.upload');
$fieldMod = NewModel('system.field');
$typeMod = NewModel('article.type');
$articleMod = NewModel('article.article'); 


//是否开启了文章投稿
if( $authorConfig['author_article_open'] == '0' )
{
	ReturnData($lang['author']['article_close']);
}
//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);

//参数获取
$id = str::Int( Post('id') , null , 0 );
$did = str::Int( Post('did') , null , 0 );
$simg = Post('simg' , C('default_simg',null,'articleConfig'));
$name = str::LNC( Post('title') , $lang['author']['article_name_err'] , '1,50' );
CheckShield( $name , $lang['author']['disable_draft_title'] , 'disable' );
$cname = Post('cname');
$tid = str::Int( Post('tid') , null , 0 );
$source = str::LNC( Post('source') , $lang['author']['article_source_err'] , '1,10' );
$tags = Post('tags');
$info = Post('info');
$content = str::ClearXSS(str::IsEmpty( Post('content',null,false) , $lang['author']['article_content_no']));
CheckShield( $content , $lang['author']['disable_draft_content'] , 'disable' );
//提取文章描述
$info = str::GetContentInfo($content,$info);
CheckShield( $info , $lang['author']['disable_article_info'] , 'disable' );

//如果分类为0就提示错误
if( $tid == '0' )
{
	ReturnData($lang['author']['article_tid_err']);
}
else if( str::LNC( $cname ,'' , '1,50' ) === false )
{
	ReturnData($lang['author']['article_cname_err']);
}
//检查分类数据
$typeData = $typeMod->GetById($tid);
//分类不存在
if( !$typeData )
{
	ReturnData($lang['author']['article_type_no']);
}
//分类不允许投稿
else if($typeData['type_add'] == '0')
{
	ReturnData($lang['author']['article_type_close']);
}
//检查是否小说重名
if( $articleMod->CheckName($name , $id) > 0 )
{
	ReturnData($lang['author']['article_article_exist']);
}

//默认插入数据
$data['article_simg'] = $simg;
$data['article_name'] = $name;
$data['article_cname'] = $cname;
$data['type_id'] = $tid;
$data['article_source'] = $source;
$data['article_tags'] = $tags;
$data['article_info'] = $info;
$data['article_content'] = $content;
//设置上传的参数
$uploadMod->module = 'author';
$uploadMod->type = 'article';
$uploadMod->uid = $uid;

//新增文章
if( $id == 0 )
{
	if( empty($tags) )
	{
		$tags = $typeData['type_name'];
	}
	$data['article_tags'] = $tags;
	
	$status = $authorConfig['author_article_createarticle'];
	$data['article_author_id'] = $author['author_id'];
	$data['article_author'] = $author['author_nickname'];
	$data['article_addtime'] = time();
	$data['article_status'] = $status;
	if( $status == 1 )
	{
		$data['article_examinetime'] = time();
	}
	//插入数据
	$id = $articleMod->Insert($data);
	//草稿是否存在
	if( $did > 0)
	{
		$draftWhere['draft_id'] = $did;
		$draftWhere['draft_author_id'] = $author['author_id'];
		$draftWhere['draft_module'] = 'article';
		$draftData = $draftMod->GetOne($draftWhere);
		//如果存在就删除草稿
		if( $draftData )
		{
			$draftMod->DelOne($did);
		}
	}
	$uploadMod->where['upload_module'] = 'author';
	$uploadMod->where['upload_type'] = 'draft';
	$uploadMod->where['upload_cid'] = $did;
	$uploadMod->where['user_id'] = $uid;
	$uploadMod->data['upload_type'] = 'article';
	$uploadMod->data['upload_cid'] = $id;
	$uploadMod->save();
	//上传文件绑定和设置缩略图
	$uploadMod->cid = $id;
	$uploadMod->FileBind('add',$content);
	
	//如果是自动审核就创建HTML
	if( $status == '1' )
	{
		$htmlMod = NewModel('system.html' , array('module'=>'article'));
		$htmlMod->CreateContentHtml($id);
	}
	
	//插入编辑申请记录
	InserApply($status,$data,$id);

	//写入自定义字段
	$fieldMod->SetFieldOption('article' , $data['type_id'] , $id , Post('field'));
	//标签插入
	$tagsMod->SetTags('article' , $data['article_tags']);
	
	ReturnData( $lang['author']['operate']['article_add_'.$status]['success'] , $ajax , 200);
}
//编辑文章
else
{	
	$where['article_id'] = $id;
	$where['article_author_id'] = $author['author_id'];
	$articleData = $articleMod->GetOne($where);
	if( !$articleData )
	{
		ReturnData( $lang['author']['article_article_no'] , $ajax);
	}
	else if($articleData['article_author_id'] != $author['author_id'])
	{
		ReturnData( $lang['author']['article_noauthor'] , $ajax);
	}
	else
	{
		$status = $authorConfig['author_article_editarticle'];

		//上传文件绑定和设置缩略图
		$uploadMod->cid = $id;
		$uploadMod->FileBind('edit',$content);
		
		//是自动审核才直接修改数据
		if( $status == 1 )
		{
			$result = $articleMod->Update($data , $id);
			//创建HTML
			$htmlMod = NewModel('system.html' , array('module'=>'article'));
			$htmlMod->CreateContentHtml($id);
		}
		else
		{
			$applyLast = $applyMod->GetLastData('article_editarticle' , $uid , $id);
			if( $applyLast && $applyLast['apply_status'] == 0 )
			{
				ReturnData($lang['author']['article_apply_no'] , $ajax);
			}
		}
		
		//写入自定义字段
		$fieldMod->SetFieldOption('article' , $data['type_id'] , $id , Post('field'));
		//插入文章编辑申请记录
		InserApply($status,$data,$id);
		
		ReturnData( $lang['author']['operate']['article_edit_'.$status]['success'] , $ajax , 200);
	}
}

	
//插入小说编辑申请记录
function InserApply($status,$articleData,$id)
{
	global $lang,$applyMod,$author;

	$data['apply_module'] = 'author';
	$data['apply_type'] = 'article_editarticle';
	$data['apply_status'] = $status;
	$data['apply_uid'] = user::GetUid();
	$data['apply_cid'] = $id;
	$data['apply_remark'] = $lang['author']['apply_remark_'.$status];
	if( $status == 0 )
	{
		$data['apply_option'] = $articleData;
	}
	$applyMod->Insert($data , 0);
}
?>