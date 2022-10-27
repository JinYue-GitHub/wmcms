<?php
/**
* 路由处理
*
* @version        $Id: route.class.php 2018年12月22日 12:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
/*
*	3为普通模式 		- 	http://localhost/?module=novel&file=read	SEO优化-低，伪静态 - 低，无需懂伪静态规则
*	4为兼容模式			-	http://localhost/?path=/novel/read			SEO优化-低，伪静态 - 低，无需懂伪静态规则
*	5为PATHINFO模式	-	http://localhost/index.php/novel/read		SEO优化-中，伪静态 - 中，无需懂伪静态规则
*	6为REWRITE模式	-	http://localhost/novel/read					SEO优化-高，伪静态 - 高，无需懂伪静态规则
*/
define('route', true);
/**
 * 获得url中的文件信息
 * @param 参数1，必须，路劲中的分割符号
 * @param 参数2，选填，路径的字符串
 * @param 参数3，选填，文件后缀字符
 */
function GetUrlParInfo($exp,$pathInfo='',$suffix='')
{
	global $C;
	$route = $C['config']['route']['urlmode_route'];
	if( $route )
	{
		$routeConfig = explode("\r\n",$route);
		foreach ($routeConfig as $k=>$v)
		{
			$route = explode('={#gt}',$v);
			$pathInfo = str_replace($route[0], $route[1], $pathInfo);
		}
	}
	//替换参数的后缀
	$pathInfo = str_replace($suffix, '', $pathInfo);
	//获得的字符串
	$routeArr = explode($exp,$pathInfo);
	//设置模块和文件
	$rs['module'] = isset($routeArr[1])?$routeArr[1]:'';
	$rs['file'] = isset($routeArr[2])?$routeArr[2]:'';
	//将参数设置为get
	unset($routeArr[0]);
	unset($routeArr[1]);
	unset($routeArr[2]);
	if( $routeArr )
	{
		$key = '';
		foreach($routeArr as $k=>$v)
		{
			if( $key != '')
			{
				$_GET[$key] = $v;
				$key = '';
			}
			else
			{
				$key = $v;
			}
		}
	}
	return $rs;
}

$routeExp = $C['config']['route']['urlmode_exp'];
$routeSuffix = $C['config']['route']['urlmode_suffix'];
$routeParOdnr1 = $C['config']['route']['urlmode_par_odnr1'];
$routeParOdnr2 = $C['config']['route']['urlmode_par_odnr2'];
$routeParCptb = $C['config']['route']['urlmode_par_cptb'];
$routeUrlType = $C['config']['route']['url_type'];

//获得url参数
switch($C['config']['route']['url_type']){
	case 3:
		$urlModule = isset($_GET[$routeParOdnr1])?$_GET[$routeParOdnr1]:'';
		$urlFile = isset($_GET[$routeParOdnr2])?$_GET[$routeParOdnr2]:'';
		break;
	case 4:
	case 5:
	case 6:
		if( $routeUrlType == '4' )
		{
			$pathInfo = $_GET[$routeParCptb];
			unset($_GET[$routeParCptb]);
		}
		else if( $routeUrlType == '6' )
		{
			$pathInfo = $_SERVER['REQUEST_URI'];
		}
		else
		{
			$pathInfo = $_SERVER['PATH_INFO'];
		}
		$routeArr = GetUrlParInfo($routeExp,$pathInfo,$routeSuffix);
		$urlModule = $routeArr['module'];
		$urlFile = $routeArr['file'];
		break;
}

//模块为空或者文件不存在
if( $urlModule == '' || $urlFile == '' )
{
}
//文件不存在
else if( !file_exists('module/'.$urlModule.'/'.$urlFile.'.php') )
{
	require_once '404.php';
	die();
}
//存在文件就引用文件
else
{
	require_once 'module/'.$urlModule.'/'.$urlFile.'.php';
	die();
}
?>