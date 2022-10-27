<?php
/**
* 创建文件夹控制器
*
* @version        $Id: data.file.createfolder.php 2016年5月12日 21:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if( $type == '' ){$type = 'create';}


$path = str::ClearPath(Request('path'),true);
$file = str::ClearPath(Request('file'));
$fileContent = '';

//如果保存的目录是以/开头，就替换掉
if( substr( $path, 0, 1 ) != '/' )
{
	$path = '/'.$path;
}

if( $file == '' )
{
	$file = 'index.html';
}
else
{
	$fileContent = file::GetFile(WMROOT.$path.$file);
}

?>