<?php
/**
* 创建/编辑文章草稿
*
* @version        $Id: article_articleedit.php 2017年2月12日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

$id = str::Int( Get('id') , null , 0);

//不检查小说状态
C('page.article_check_status',0);
//查询文章是否存在
$where['article_id'] = $id;
$where['article_author_id'] = author::GetUid();
$data = str::GetOne(article::GetData( 'content' , $where , $lang['system']['content']['no'] ));

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_article_articleedit' ,
	'dtemp'=>'author/article_articleedit.html',
	'id'=>$id,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'ArticleArticleEditLabel',
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>