<?php
/**
* 作者基本资料
*
* @version        $Id: basic.php 2016年12月22日 19:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月14日 16:47 weimeng
*
*/
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_basic' ,
	'dtemp'=>'author/basic.html',
	'label'=>'authorlabel',
	'label_fun'=>'BasicLabel',
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>