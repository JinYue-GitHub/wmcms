<?php
/**
* 回帖上传附件请求处理
*
* @version        $Id: bbsreplay.php 2019年07月20日 18:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$bid = str::Int( Request('bid') , $lang['upload']['replay_par_err'] );
if ( $bbsConfig['replay_open'] == '0' )
{
	ReturnData( $lang['upload']['replay_close'] , $ajax );
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

$bbsMod = NewModel('bbs.bbs');
if( $bbsMod->GetOne($bid) )
{
	$mid = $bid;
}
else
{
	ReturnData( $lang['upload']['no_content'] , $ajax );
}
?>