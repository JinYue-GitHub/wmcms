<?php
/**
* 新增/编辑小说操作处理
*
* @version        $Id: novel_noveledit.php 2016年12月25日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$novelMod = NewModel('novel.novel');
$chapterMod = NewModel('novel.chapter');
$authorMod = NewModel('author.author');
$tagsMod = NewModel('system.tags');
$applyMod = NewModel('system.apply' , 'author');
$uploadMod = NewModel('upload.upload');
$fieldMod = NewModel('system.field');
$typeMod = NewModel('novel.type');
$groupMod = NewModel('editor.group');
$worksMod = NewModel('editor.works');

//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);

//参数获取
$id = str::Int( Post('id') , null , 0 );
$coverId = str::Int(Post('cover_id'),null,0);
$groupId = str::Int(Post('group_id'),null,0);
$process = str::Int( Post('process') , null , 1 );
$type = str::Int( Post('type') , null , 1 );
$tid = str::Int( Post('tid') , null , 0 );
//标签
$tags = implode(',',$tagsMod->GetByNameKey('novel',Post('tags')));
//小说名字长度检查
$name = Post('name');
str::CheckLen( $name , '1,20' , $lang['author']['novel_name_err'] );
$name = str::DelHtml($name);
//小说简介长度检查
$intro = Post('intro');
str::CheckLen( $intro , '20,3000' , $lang['author']['novel_intro_err'] );
$intro = str::DelHtml($intro);
CheckShield( $intro , $lang['author']['disable_novel_info'] , 'disable' );

//检查是否小说重名
if( $novelMod->CheckName($name , $id) > 0 )
{
	ReturnData($lang['author']['novel_novel_exist']);
}

//检查小说拼音是否重名
$pinyinClass = NewClass('pinyin');
$pinyin = $pinyinClass->topy($name);
$pinYinCount = $novelMod->CheckPinYin($pinyin , $id);
if( $pinYinCount > 0 )
{
	$pinyin = $pinyin.$author['author_id'];
}

//封面是否是自动审核,并且封面文件ID大于0就设置封面
$cover = C('cover',null,'novelConfig');
if( $authorConfig['author_novel_uploadcover'] == 1 && $coverId>0 )
{
    $uploadData = $uploadMod->GetOne($coverId);
    if( $uploadData )
    {
        $cover = $uploadData['upload_img'];
    }
}

//默认插入数据
$data['novel_info'] = $intro;
$data['novel_name'] = $name;
$data['novel_pinyin'] = $pinyin;
$data['novel_tags'] = $tags;
//新增小说
if( $id == 0 )
{
    //检查编辑组是否存在
    if( !$groupMod->GetById($groupId) )
    {
	    ReturnData($lang['author']['editor_group_no']);
    }
    
    $data['novel_cover'] = $cover;
	$data['novel_process'] = $process;
	$data['novel_type'] = $type;
	$data['type_id'] = $tid;

	//如果分类为0就提示错误
	str::CheckElse($tid, 0 , $lang['author']['novel_tid_err']);
	$typeData = $typeMod->GetById($tid);
	if( !$typeData )
	{
		ReturnData($lang['author']['novel_type_no']);
	}
	//如果标签为空就设置为当前分类为标签
	if( empty($tags) )
	{
	    $data['novel_tags'] = $typeData['type_name'];
	}
	
	$status = $authorConfig['author_novel_createnovel'];
	$data['author_id'] = $author['author_id'];
	$data['novel_author'] = $author['author_nickname'];
	$data['novel_createtime'] = time();
	$data['novel_uptime'] = time();
	$data['novel_status'] = $authorConfig['author_novel_createnovel'];
	//插入数据
	$id = $novelMod->Insert($data);
	
	//插入小说编辑申请记录
	$data['type_name'] = $typeData['type_name'];
	InserApply($status,$data,$id);

	//修改申请记录id和上传封面的id
	$applyMod->UpdateCid('novel_cover',$id);
	$uploadMod->UpdateCid('author','novel_cover',$id);
	//追加编辑工作组关联
	$worksMod->BindGroup($groupId,$id);

	//写入自定义字段
	$fieldMod->SetFieldOption('novel' , $data['type_id'] , $id , Post('field'));
	$info = $lang['author']['operate']['novel_add_'.$status]['success'];
}
//编辑小说
else
{	
	$where['novel_id'] = $id;
	$where['author_id'] = $author['author_id'];
	$novelData = $novelMod->GetOne($where);
	$typeData = $typeMod->GetById($novelData['type_id']);
	if( !$novelData )
	{
		ReturnData( $lang['author']['novel_novel_no'] , $ajax);
	}
	else if($novelData['author_id'] != $author['author_id'])
	{
		ReturnData( $lang['author']['novel_noauthor'] , $ajax);
	}
	else
	{
		$status = $authorConfig['author_novel_editnovel'];
		//封面和原始封面一样
		if( $novelData['novel_cover'] == Post('cover') )
		{
			unset($data['novel_cover']);
		}
    	//如果标签为空就设置为当前分类为标签
    	if( empty($tags) )
    	{
	        $data['novel_tags'] = $typeData['type_name'];
    	}
		
		//是自动审核才直接修改数据
		if( $status == 1 )
		{
			$result = $novelMod->Update($data , $id);
			//修改小说分类移动文件
			if( $result && isset($data['type_id']) )
			{
				$novelMod->MoveNovelFolder($novelData['type_id'],$data['type_id'],$id);
			}
		}
		else
		{
			$applyLast = $applyMod->GetLastData('novel_editnovel' , $uid , $id);
			if( $applyLast && $applyLast['apply_status'] == 0 )
			{
				ReturnData($lang['author']['novel_apply_no'] , $ajax);
			}
		}
		
		//写入自定义字段
		$fieldMod->SetFieldOption('novel' , $novelData['type_id'] , $id , Post('field'));
		
		//插入小说编辑申请记录
		$data['type_name'] = $typeData['type_name'];
		InserApply($status,$data,$id);
		
		$info = $lang['author']['operate']['novel_edit_'.$status]['success'];
	}
}

//标签插入
$tagsMod->SetTags('novel' , $data['novel_tags']);
ReturnData( $info , $ajax , 200);
	
//插入小说编辑申请记录
function InserApply($status,$novelData,$id)
{
	global $lang,$applyMod,$author,$uid;
	
	$data['apply_module'] = 'author';
	$data['apply_type'] = 'novel_editnovel';
	$data['apply_status'] = $status;
	$data['apply_uid'] = $uid;
	$data['apply_cid'] = $id;
	$data['apply_remark'] = $lang['author']['apply_remark_'.$status];
	//需要保存的数据
	unset($novelData['novel_cover']);
	$data['apply_option'] = $novelData;
	//如果是自动审核就插入时间
	if( $data['apply_status'] == '1' )
	{
		$data['apply_updatetime'] = time();
	}
	$applyMod->Insert($data , 0);
}
?>