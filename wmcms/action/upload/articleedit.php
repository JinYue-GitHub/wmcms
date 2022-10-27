<?php
/**
* 用户上传文章缩略图请求处理
*
* @version        $Id: articlesimg.php 2017年2月11日 12:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( $uid , 0 , $lang['upload']['no_login'] );
//是否是作者
$authorMod = NewModel('author.author');
$author = $authorMod->GetAuthor();
if( !$author )
{
	ReturnData($lang['upload']['no_author'] , $ajax);
}

//设置模块
$module = 'author';
//设置图片默认描述
$alt = $_FILES[$fileBtnName]['name'];
//如果不是图片设置保存文件夹
if( !str::IsImg($_FILES[$fileBtnName]['type']) )
{
	$filePath = $upPath.'files';
}
?>