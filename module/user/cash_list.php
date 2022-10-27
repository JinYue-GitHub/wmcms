<?php
/**
* 提现申请记录
*
* @version        $Id: cash_list.php 2017年4月4日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月14日 16:47 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

$page = str::Page();

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_cash_list' ,
	'dtemp'=>'user/cash_list.html',
	'label'=>'userlabel',
	'label_fun'=>'CashListLabel',
	'page'=>$page,
	'listurl'=>tpl::url( 'user_cash_list' , array('page'=>'{page}') ),
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>