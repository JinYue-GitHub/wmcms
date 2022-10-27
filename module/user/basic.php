<?php
/**
* 用户基本资料
*
* @version        $Id: basic.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月15日 18:55 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_basic' ,
	'dtemp'=>'user/basic.html',
	'label'=>'userlabel',
	'label_fun'=>'BasicLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>