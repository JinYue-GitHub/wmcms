<?php
/**
 * 注册登录
 *
 * @version        $Id: reg.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月12日 19:55 weimeng
 *
 */
//引入模块公共文件
require_once 'user.common.php';


//是否登录了
str::RT( user::GetUid() , 0 , $lang['user']['islogin'] );
//登录是否关闭
str::EQ( $userConfig['login_open'] , 0 , $lang['user']['reg_close'] );


//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_reg' ,
	'dtemp'=>'user/reg.html',
	'label'=>'userlabel',
	'label_fun'=>'RegLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>