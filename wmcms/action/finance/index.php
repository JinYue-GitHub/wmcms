<?php
/**
* 充值卡使用处理器验证处理器
*
* @version        $Id: index.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$orderMod = NewModel('finance.finance_order');
$userMod = NewModel('user.user');
//财务配置
$financeConfig = GetModuleConfig('finance' , true);
	
//是否登录了
$uid = user::GetUid();
str::EQ( $uid , 0 , $lang['user']['no_login']['info'] );
require_once $type.'.php';
?>