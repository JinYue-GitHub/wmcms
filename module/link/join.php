<?php
/**
 * 加入友链
 *
 * @version        $Id: join.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月5日 21:10 weimeng
 *
 */
//引入模块公共文件
require_once 'link.common.php';


//检查友链申请功能
str::EQ( $linkConfig['join'] , 0 , $lang['link']['par']['join_close'] );

//获得页面的标题等信息
C('page' ,  array('pagetype'=>'link_join' ,'dtemp'=>'link/join.html',));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>