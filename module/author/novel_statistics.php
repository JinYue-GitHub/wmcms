<?php
/**
 * 小说数据统计
 *
 * @version        $Id: novel_statistics.php 2022年04月05日 21:21  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_novel_statistics' ,
	'dtemp'=>'author/novel_statistics.html',
	'label'=>'authorlabel',
	'label_fun'=>'NovelStatisticsLabel',
	'listurl'=>tpl::url('author_novel_statistics'),
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>