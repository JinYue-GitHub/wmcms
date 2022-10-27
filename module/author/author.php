<?php
/**
* 用户收藏、书架、订阅等信息列表
*
* @version        $Id: author.php 2019年06月15日 12:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月21日 14:31 weimeng
*
*/
$ClassArr = array('page');
$checkLogin = false;
//引入模块公共文件
require_once 'author.common.php';

//作者个人介绍页面，公众页面，不检测状态
//author::CheckAuthor();

//当前页面的参数检测
$aid = str::Int( Get('aid') , null , 0);
$data = str::GetOne(author::GetData( 'author' , 'a.author_id='.$aid , $lang['author']['par']['author_no'] ));

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_author',
	'dtemp'=>'author/author.html',
	'label'=>'authorlabel',
	'label_fun'=>'AuthorLabel',
	'data'=>$data,
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>