<?php
/**
* 文章首页
*
* @version        $Id: index.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月28日 21:15 weimeng
*
*/
//引入模块公共文件
require_once 'article.common.php';


//获得页面的标题等信息
C('page' ,  array('pagetype'=>'article_index' ,'dtemp'=>'article/index.html',));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>