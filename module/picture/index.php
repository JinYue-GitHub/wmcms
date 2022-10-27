<?php
/**
* 图集首页
*
* @version        $Id: index.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月4日 10:55 weimeng
*
*/
//引入模块公共文件
require_once 'picture.common.php';


//获得页面的标题等信息
C('page' ,  array('pagetype'=>'picture_index' ,'dtemp'=>'picture/index.html',));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>