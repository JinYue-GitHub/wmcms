<?php
/**
* 用户充值
*
* @version        $Id: charge.php 2017年4月2日 11:04  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_charge' ,
	'dtemp'=>'user/charge.html',
	'label'=>'userlabel',
	'label_fun'=>'ChargeLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>