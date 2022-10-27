<?php
/**
* 获得登录页的模版
*
* @version        $Id: login.php 2016年9月15日 21:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//设置页面信息
C('page',array('pagetype'=>'index','dtemp'=>$file.'.html'));
tpl::GetSeo();


//new一个模版类，然后输出
$tpl=new tpl();

$arr = array(
	'登录后操作'=>$userConfig['ajax_login'],
);
tpl::Rep($arr);

$tpl->display();
?>