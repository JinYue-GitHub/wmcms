<?php
/**
* 作者申请
*
* @version        $Id: apply.php 2016年12月18日 15:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'author.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
//检查作者的状态
author::CheckAuthor(false);

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_apply' ,
	'dtemp'=>'author/apply.html',
	'label'=>'authorlabel',
	'label_fun'=>'ApplyLabel',
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>