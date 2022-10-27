<?php
/**
 * 新增留言页
 *
 * @version        $Id: add.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月5日 20:05 weimeng
 *
 */
//引入模块公共文件
require_once 'message.common.php';

$content = Get('content');

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'message_add' ,
	'dtemp'=>'message/add.html',
	'label'=>'messagelabel',
	'label_fun'=>'MessageLabel',
	'content'=>$content
));
//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>