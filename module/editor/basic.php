<?php
/**
* 编辑个人资料首页
*
* @version        $Id: basic.php 2022年05月16日 14:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'editor.common.php';

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'editor_basic',
	'dtemp'=>'editor/basic.html',
	'label'=>'editorlabel',
	'label_fun'=>'BasicLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>