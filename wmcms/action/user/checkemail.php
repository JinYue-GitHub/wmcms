<?php
/**
* 邮箱地址检查请求处理
*
* @version        $Id: chaeckeamil.php 2016年5月28日 17:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$email = str::IsEmpty( Post('email') , $lang['user']['email_no'] );
//邮件是否正确
str::CheckEmail( $email , $lang['user']['email_err'] );
//检查邮箱是否被注册
user::CheckEmail( $email , $lang['user']['email_exist'] );

ReturnData($lang['user']['email_success'], $ajax , 200);
?>