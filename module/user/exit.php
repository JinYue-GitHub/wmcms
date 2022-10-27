<?php
/**
* 退出
*
* @version        $Id: exit.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2017年7月31日 10:56 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
Cookie('user_account','delete');
Cookie('user_nickname','delete');

if( Request('ajax') == 'yes' )
{
	ReturnData($lang['user']['exit'] , true , 200);
}
else
{
	tpl::ErrInfo($lang['user']['exit'],tpl::Url('index'),3);
}
?>