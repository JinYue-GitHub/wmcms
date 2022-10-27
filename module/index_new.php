<?php
/**
* 网站首页
*
* @version        $Id: index.php 2018年12月22日 11:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月4日 17:35  weimeng
*
*/
//安装检查
if( !file_exists('wmcms/config/install.lock.txt') )
{
	header("Location: /install/index.php"); 
}

//引入路由配置和类文件
require_once 'wmcms/config/route.config.php';
require_once 'wmcms/inc/route.class.php';

//载入类文件
$C['module']['inc']['class']=array('file','str');
//设置使用模块功能
$C['module']['inc']['module']=array('all');

//引入公共文件
require_once 'wmcms/inc/common.inc.php';

//如果是静态路径
if( GetKey($C,'config,route,ishtml') == '1' && C('ua.pt_int') == '4')
{
	header('Location: '.tpl::url('index'));
}
//设置页面信息
C('page',array('pagetype'=>'index','dtemp'=>'index.html'));
tpl::GetSeo();

//new一个模版类，然后输出
$tpl=new tpl();
$tpl->display();
?>