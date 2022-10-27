<?php
/**
* 保存作者基本资料操作处理
*
* @version        $Id: upbasic.php 2016年12月25日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否是作者
$authorMod = NewModel('author.author');
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);

$info = str::DelHtml(Post('info'));
//禁用词判断
CheckShield( $info , $lang['author']['disable_basic_info'] , 'disable' );

$notice = str::DelHtml(Post('notice'));
//禁用词判断
CheckShield( $notice , $lang['author']['disable_basic_notice'] , 'disable' );

//设置修改数据
$data['author_info'] = $info;
$data['author_notice'] = $notice;
//保存数据
$authorMod = NewModel('author.author');
$result = $authorMod->UpdateAuthor($data);

//保存成功
if( $result )
{
	ReturnData( $lang['author']['operate']['upbasic']['success'] , $ajax , 200);
}
else
{
	ReturnData( $lang['author']['operate']['upbasic']['fail'] , $ajax);
}
?>