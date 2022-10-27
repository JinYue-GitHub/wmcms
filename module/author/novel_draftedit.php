<?php
/**
* 创建/编辑小说草稿
*
* @version        $Id: novel_draftedit.php 2017年1月4日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

$id = str::Int( Get('nid') , null , 0);
$did = str::Int( Get('did') , null , 0);

//检查是否存在小说
$data = author::CheckContent('novel', $id , $lang['author']['par']['novel_no']);
//查询草稿
if($did > 0)
{
	$where['draft_module'] = 'novel';
	$where['draft_cid'] = $id;
	$where['draft_id'] = $did;
	$where['draft_author_id'] = author::GetUid();
	$draftData = str::GetOne(author::GetData( 'draft' , $where , $lang['system']['content']['no'] ));
	//合并数据
	$data = array_merge($data,$draftData);
}
else
{
	$data['draft_createtime'] = time();
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_novel_draftedit' ,
	'dtemp'=>'author/novel_draftedit.html',
	'id'=>$id,
	'did'=>$did,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'NovelDraftEditLabel',
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>