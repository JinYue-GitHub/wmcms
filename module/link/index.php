<?php
/**
 * 友链首页
 *
 * @version        $Id: index.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月6日 21:10 weimeng
 *
 */
//引入模块公共文件
require_once 'link.common.php';


//获得页面的标题等信息
C('page' ,  array('pagetype'=>'link_index' ,'dtemp'=>'link/index.html',));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>