<?php
/**
* 绑定页的模版
*
* @version        $Id: login.php 2016年9月15日 21:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
$bindType = Get('type','tel');
//设置页面信息
C('page',array('pagetype'=>'user_bind','dtemp'=>'user/bind.html','label'=>'userlabel','label_fun'=>'BindLabel',));
tpl::GetSeo();
//new一个模版类，然后输出
$tpl=new tpl();
$arr = array(
	'绑定类型'=>Get('type','tel'),
);
tpl::Rep($arr);
$tpl->display();
?>