<?php
/**
* 网站地图
*
* @version        $Id: sitemap.php 2017年2月19日 11:23  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月4日 17:35  weimeng
*
*/
//载入类文件
$C['module']['inc']['class']=array('file','str');
//设置使用模块功能
$C['module']['inc']['module']=array('all');

//引入公共文件
require_once '../../../wmcms/inc/common.inc.php';

//设置页面信息
$mark = '';
$type = Request('type','html');
switch ($type)
{
	case 'html':
		$dTemp = 'map_html/index.html';
		break;
	case 'xml':
		$mark = 'xml';
		$dTemp = 'map_xml/index.html';
		break;
	case 'site':
		$mark = 'xml';
		$dTemp = 'map_site/index.html';
		break;
	case 'rss':
		$dTemp = 'map_rss/index.html';
		break;
	default:
		$dTemp = 'map_html/index.html';
		break;
}

C('page',array('pagetype'=>'index','dtemp'=>'templates/system/'.$dTemp,'tempid'=>'reset'));
tpl::GetSeo();

//new一个模版类，然后输出
$tpl=new tpl();
$tpl->display($mark);
?>