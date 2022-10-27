<?php
/**
* 应用搜索模块
*
* @version        $Id: search.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月28日 9:32 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'app.common.php';

//当前页面的参数检测
$key = str::IsEmpty( Get('key') , $lang['app']['par']['key_no']);
$page = str::Page( Get('page') );
$type = str::In( Get('type') , array(array('0','1','2','3'),1) );


//参数长度验证
str::CheckLen( $key , '2,16' , $lang['app']['par']['key_err'] );


C('page' ,  array(
	'pagetype'=>'app_search' ,
	'dtemp'=>'app/search.html',
	'label'=>'applabel',
	'label_fun'=>'SearchLabel',
	'key'=>$key,
	'type'=>$type,
	'page'=>$page,
	'listurl'=>tpl::url('app_search',array('type'=>$type,'key'=>$key)),
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>