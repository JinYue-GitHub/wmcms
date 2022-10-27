<?php
/**
 * 小说首页
 *
 * @version        $Id: index_girl.php 2017年7月8日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
//引入模块公共文件
require_once 'novel.common.php';

//获得页面的标题等信息
C('page' ,  array('pagetype'=>'novel_index_girl' ,'dtemp'=>'novel/index_girl.html'));
//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>