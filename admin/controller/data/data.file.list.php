<?php
/**
* 文件管理器
*
* @version        $Id: data.file.list.php 2016年5月11日 13:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$fileSer = AdminNewClass('data.file');

//获取当前文件夹的位置
$path = str::ClearPath(Request('path'),true);
$prePath = '';
$nowPath = $path;

//如果不是根目录
if( $path != '' )
{
	//如果最后不是以斜杠结尾
	if( substr($path, -1) != '/' )
	{
		$nowPath = $path.'/';
	}
	
	$prePathArr = explode('/', $path);
	if( count($prePathArr) > 1)
	{
		$prePath = str_replace('/'.$prePathArr[count($prePathArr)-1], '', $path);
	}
}

//文件夹列表
$floderArr = file::FloderList(WMROOT.$path);
//文件列表
$fileArr = file::FileList(WMROOT.$path);
?>