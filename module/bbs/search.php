<?php
/**
* 帖子搜索搜索
*
* @version        $Id: search.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月29日 11:18 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'bbs.common.php';

//当前页面的参数检测
$key = str::IsEmpty( Get('key') , $lang['bbs']['key_no']);
$page = str::Page( Get('page') );
$type = str::In( Get('type') , array(array('0','1','2','3'),1) );


//参数长度验证
str::CheckLen( $key , '2,16' , $lang['bbs']['key_err'] );


C('page' ,  array(
	'pagetype'=>'bbs_search' ,
	'dtemp'=>'bbs/search.html',
	'label'=>'bbslabel',
	'label_fun'=>'SearchLabel',
	'key'=>$key,
	'type'=>$type,
	'page'=>$page,
	'listurl'=>tpl::url('bbs_search',array('type'=>$type,'key'=>$key)),
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>