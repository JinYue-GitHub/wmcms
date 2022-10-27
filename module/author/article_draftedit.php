<?php
/**
* 创建/编辑文章草稿
*
* @version        $Id: article_draftedit.php 2017年2月11日 14:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

$did = str::Int( Get('did') , null , 0);

//查询草稿
$data = array();
if($did > 0)
{
	$where['draft_module'] = 'article';
	$where['draft_id'] = $did;
	$where['draft_author_id'] = author::GetUid();
	$data = str::GetOne(author::GetData( 'draft' , $where , $lang['system']['content']['no'] ));
}
else
{
	$data['draft_createtime'] = time();
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_article_draftedit' ,
	'dtemp'=>'author/article_draftedit.html',
	'did'=>$did,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'ArticleDraftEditLabel',
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>