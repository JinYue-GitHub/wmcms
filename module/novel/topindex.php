<?php
/**
 * 小说排行首页
 *
 * @version        $Id: topindex.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月9日 10:40 weimeng
 *
 */
//引入模块公共文件
require_once 'novel.common.php';

//设置seo信息
C('page' ,  array(
	'pagetype'=>'novel_topindex',
	'dtemp'=>'novel/topindex.html',
));
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>