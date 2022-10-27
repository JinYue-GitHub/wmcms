<?php
/**
* 检查作家笔名是否被注册
*
* @version        $Id: checknickname.php 2016年12月19日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nickname = str::IsEmpty( Post('nickname') , $lang['author']['nickname_no'] );

//账号长度和账号格式
str::CheckLen( $nickname , '2,12' , $lang['author']['nickname_len']  );
//只能是字母和数字组合
str::LNC( $nickname, $lang['author']['nickname_err'] );

//查询账号是否被注册
$authorMod = NewModel('author.author');
if( $authorMod->CheckNickName( $nickname ) == false )
{
	$code = 300;
	$info = $lang['author']['nickname_exist'];
}
else
{
	$code = 200;
	$info = $lang['author']['nickname_success'];
}

ReturnData($info, $ajax , $code);
?>