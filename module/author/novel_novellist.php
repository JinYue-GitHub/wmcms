<?php
/**
* 小说作品列表首页
*
* @version        $Id: novel_novellist.php 2016年12月18日 15:43  weimeng
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

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_novel_novellist' ,
	'dtemp'=>'author/novel_novellist.html',
	'page'=>$page,
	'label'=>'authorlabel',
	'label_fun'=>'NovelNovelListLabel',
	'listurl'=>tpl::url('author_novel_novellist'),
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>