<?php
/**
* 发帖上传附件请求处理
*
* @version        $Id: bbspost.php 2015年8月15日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月28日 12:44  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

if ( $bbsConfig['post_open'] == '0' )
{
	ReturnData( $lang['upload']['post_close'] , $ajax );
}
	
//是否登录了
str::EQ( $uid , 0 , $lang['upload']['no_login'] );

//设置模块
$module = 'bbs';
//设置图片默认描述
$alt = $_FILES[$fileBtnName]['name'];

//如果不是图片设置保存文件夹
if( !str::IsImg($_FILES[$fileBtnName]['type']) )
{
	$filePath = $upPath.'files';
}
?>