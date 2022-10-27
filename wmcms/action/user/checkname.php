<?php
/**
* 用户ajax请求处理
*
* @version        $Id: system.php 2015年8月15日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年1月16日 11:44  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$name = str::IsEmpty( Post('name') , $lang['user']['name_no'] );

//账号长度和账号格式
str::CheckLen( $name , '4,16' , $lang['user']['name_len']  );
//只能是字母和数字组合
str::LN( $name, $lang['user']['name_err'] );
//查询账号是否被注册
user::CheckName( $name , $lang['user']['name_exist'] );

ReturnData($lang['user']['name_success'], $ajax , 200);
?>