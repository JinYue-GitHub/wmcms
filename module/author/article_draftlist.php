<?php
/**
* 文章草稿列表
*
* @version        $Id: article_draftlist.php 2017年1月11日 14:43  weimeng
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
	'pagetype'=>'author_article_draftlist' ,
	'dtemp'=>'author/article_draftlist.html',
	'label'=>'authorlabel',
	'label_fun'=>'ArticleDraftListLabel',
	'page'=>$page,
	'listurl'=>tpl::url('author_article_draftlist'),
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>