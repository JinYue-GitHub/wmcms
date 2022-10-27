<?php
/**
* 404错误页面
*
* @version        $Id: 404.php 2016年5月21日 17:59  weimeng
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


//开启了404页面统计
if( NOTFOUND )
{
	$seoMod = NewModel('system.seo');
	$seoMod->AddErrPage(404);
}

//设置页面信息
C('page',array('pagetype'=>'index','dtemp'=>'404.html'));
tpl::GetSeo();

//new一个模版类，然后输出
//@header('HTTP/1.0 404 Not Found');
//@header("status: 404 Not Found"); 
$tpl=new tpl();
$tpl->display();
?>