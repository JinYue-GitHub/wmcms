<?php
/**
* 评论列表
*
* @version        $Id: list.php 2018年8月20日 20:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'replay.common.php';

//当前页面的参数检测
$module = Get('module');
$page = str::Page( Get('page') );

C('page' ,  array(
	'pagetype'=>'index' ,
	'dtemp'=>'replay/list.html',
	'label'=>'replaylabel',
	'label_fun'=>'ListLabel',
	'module'=>$module,
	'page'=>$page,
	'listurl'=>'/module/replay/list.php?module='.$module.'&page={page}',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>