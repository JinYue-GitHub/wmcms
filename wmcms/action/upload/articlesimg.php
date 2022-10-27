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
$type = 'article_simg';
//设置图片默认描述
$alt = '文章封面上传';
//允许上传类型
$uploadType = 'jpg,jpeg,png,gif';
//剪裁
$uploadCut = 0;
//水印
$waterMark = 0;
//允许上传的大小
$uploadSize = '1024';
?>