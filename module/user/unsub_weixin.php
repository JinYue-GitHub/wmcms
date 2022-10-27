<?php
/**
* 微信用户未关注页面
*
* @version        $Id: unsub_weixin.php 2019年03月15日 20:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'user.common.php';

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'index' ,
	'dtemp'=>'user/unsub_weixin.html',
));
//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>