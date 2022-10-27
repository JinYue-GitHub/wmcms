<?php
/**
* 修改头像
*
* @version        $Id: home.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月16日 10:11 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_head' ,
	'dtemp'=>'user/head.html',
	'label'=>'userlabel',
	'label_fun'=>'HeadLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>