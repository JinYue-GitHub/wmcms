<?php
/**
* 签到
*
* @version        $Id: sign.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月19日 10:00 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
//是否开启了登录
str::NEQ( $userConfig['sign_open'] , 1 , $lang['user']['sign_close'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_sign' ,
	'dtemp'=>'user/sign.html',
	'label'=>'userlabel',
	'label_fun'=>'SignLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>