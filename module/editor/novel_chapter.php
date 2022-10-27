<?php
/**
* 编辑新书审核
*
* @version        $Id: novel_chapter.php 2022年05月19日 14:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'editor.common.php';

$where = array();
$status = Get('status');
$page = str::Page( Get('page') );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'editor_novel_chapter',
	'dtemp'=>'editor/novel_chapter.html',
	'label'=>'editorlabel',
	'label_fun'=>'NovelChapterLabel',
	'status'=>$status,
	'page'=>$page,
	'listurl'=>tpl::url('editor_novel_chapter',array('status'=>$status)),
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>