<?php
/**
* 500错误页面
*
* @version        $Id: 500.php 2017年6月4日 21:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//载入类文件
$C['module']['inc']['class']=array('file','str');
//设置使用模块功能
$C['module']['inc']['module']=array('all');
//引入公共文件
require_once 'wmcms/inc/common.inc.php';


//开启了500页面统计
if( SERVERER )
{
	$seoMod = NewModel('system.seo');
	$seoMod->AddErrPage(500);
}

//设置页面信息
C('page',array('pagetype'=>'index','dtemp'=>'500.html'));
tpl::GetSeo();

//new一个模版类，然后输出
//@header('HTTP/1.1 500 Internal Server Error');
//@header("status: 500 Internal Server Error");

$tpl=new tpl();
$tpl->display();
?>