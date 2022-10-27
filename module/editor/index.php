<?php
/**
* 编辑首页
*
* @version        $Id: index.php 2022年05月13日 16:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月21日 14:31 weimeng
*
*/
//引入模块公共文件
require_once 'editor.common.php';

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'editor_index',
	'dtemp'=>'editor/index.html',
	'label'=>'editorlabel',
	'label_fun'=>'IndexLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>