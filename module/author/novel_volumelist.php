<?php
/**
* 小说分卷列表
*
* @version        $Id: novel_volumelist.php 2017年1月4日 21:43  weimeng
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
	'pagetype'=>'author_novel_volumelist' ,
	'dtemp'=>'author/novel_volumelist.html',
	'id'=>$id,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'NovelVolumeListLabel',
	'page'=>$page,
	'listurl'=>tpl::url('author_novel_volumelist',array('nid'=>$id)),
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>