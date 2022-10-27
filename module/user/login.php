<?php
/**
* 用户登录
*
* @version        $Id: login.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月12日 10:15 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';


//是否登录了
str::RT( user::GetUid() , 0 , $lang['user']['islogin'] );
//登录是否关闭
str::EQ( $userConfig['login_open'] , 0 , $lang['user']['login_close'] );


//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_login' ,
	'dtemp'=>'user/login.html',
	'label'=>'userlabel',
	'label_fun'=>'LoginLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>