<?php
/**
* html文件更新
*
* @version        $Id: html.php 2019年01月04日 14:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//模块
$module = Request('module','index');
//使用文件名字
$page = Request('page','index');
//使用分类id
$tid = Request('tid','0');
//数据名字
$par = Request('par');
//是否需要更新html
$isUpdate = false;
//是否开启了纯静态路径
$isHtml = $C['config']['route']['ishtml'];

//如果不是纯静态url模式就不生成
if( $isHtml != '1' )
{
	ReturnData($lang['sys']['html_close'] , $ajax );
}
//如果是首页
else if( $module == 'index' )
{
	$url = '/index.php?'.$par;
	$path = $C['config']['seo']['urls']['index']['url2'];
	$cacheTime = $C['config']['web']['cache_index'];
}
//不是首页就定向到模块
else if( file_exists(WMMODULE.$module.'/'.$page.'.php') && $tid > '0')
{
	$url = '/module/'.$module.'/'.$page.'.php?'.$par;
	//列表页
	if( $page == 'type' )
	{
		$cacheTime = $C['config']['web']['cache_module_type'];
		$page = 'list';
	}
	//内容页面或者阅读页面
	else if( $module == $page || $page == 'read' )
	{
		$cacheTime = $C['config']['web']['cache_module_content'];
	}
	//其他页面
	else
	{
		$cacheTime = $C['config']['web']['cache_module_'.$page];
	}
	$path = GetKey($C,'config,seo,htmls,'.$module.','.$tid.','.$page.',path4');
}
//排行首页和排行列表
else if( isset($C['config']['web']['cache_module_'.$page]) )
{
	$url = '/module/'.$module.'/'.$page.'.php?'.$par;
	$path = $C["config"]["seo"]["urls"][$module.'_'.$page]['url2'];
	$cacheTime = $C['config']['web']['cache_module_'.$page];
}
else
{
	ReturnData($lang['sys']['html_file_no'] , $ajax);
}

//需要保存的html路径不存在
if( $path == '' )
{
	ReturnData($lang['sys']['html_path_no'] , $ajax);
}
else
{
	//判断是否有参数需要分割
	if( $par != '' )
	{
		$parArr = UrlFormat($par);
		$pathPar = tpl::Tag('{[a]}',$path);
		//地址中存在需要替换的参数
		if( !empty($pathPar[1]) )
		{
			//循环查找
			foreach ($pathPar[1] as $k=>$v)
			{
				//地址中的参数在par的参数中存在就执行替换
				if( array_key_exists($v, $parArr) )
				{
					$path = str_replace($pathPar[0][$k], $parArr[$v], $path);
				}
			}
		}
	}
	
	//文件存在
	if( file_exists(WMROOT.$path) )
	{
		//最后更新时间
		$uptime = filemtime(WMROOT.$path);
		//判断是否超过了缓存更新时间
		if( time()-$uptime > $cacheTime)
		{
			$isUpdate = true;
		}
	}
	else
	{
		$isUpdate = true;
	}
	
	
	//创建html文件
	if( $isUpdate )
	{
		$httpSer = NewClass('http');
		$html = $httpSer->GetUrl(DOMAIN.$url);
		file::CreateFile(WMROOT.$path, $html , 1);
	}
	ReturnJson('',200);
}
?>