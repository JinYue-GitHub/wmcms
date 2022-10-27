<?php
/**
* 原创模块上传附件请求处理
*
* @version        $Id: author.php 2019年07月20日 18:19  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//设置模块
$module = 'author';
$typeArr = array( 'draft','article' );
$type = str::IsEmpty( Request('module') , $lang['upload']['draft_module_no']);
if ( in_array($type, $typeArr) )
{
	//是否登录了
	str::EQ( $uid , 0 , $lang['upload']['no_login'] );
	//设置图片默认描述
	$alt = $_FILES[$fileBtnName]['name'];
	//如果不是图片设置保存文件夹
	if( !str::IsImg($_FILES[$fileBtnName]['type']) )
	{
		$filePath = $upPath.'files';
	}
}
else
{
	ReturnData($lang['upload']['no_data'] , $ajax);
}
?>