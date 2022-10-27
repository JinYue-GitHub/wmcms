<?php
/**
* 小说章节列表
*
* @version        $Id: novel_chapterlist.php 2017年1月8日 14:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

$page = str::Page( Get('page') );
$id = str::Int( Get('nid') , null , 0);

//检查是否存在小说
$data = author::CheckContent('novel', $id , $lang['author']['par']['novel_no']);

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_novel_chapterlist' ,
	'dtemp'=>'author/novel_chapterlist.html',
	'id'=>$id,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'NovelChapterListLabel',
	'page'=>$page,
	'listurl'=>tpl::url('author_novel_chapterlist',array('nid'=>$id)),
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>