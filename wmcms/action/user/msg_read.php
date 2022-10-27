<?php
/**
* 消息全部已读操作处理
*
* @version        $Id: msg_read.php 2020年06月06日 08:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

//new一个消息模型
$msgMod = NewModel('user.msg');
//消息全部已读
$result = $msgMod->Read(user::GetUid());

$info['time'] = 1;
$info['html'] = $lang['system']['operate']['autojump'];
$info['gourl'] = tpl::url( 'user_msglist');
$info['info'] = $lang['user']['operate']['msgread']['success'];
ReturnData( $info , $ajax , 200);
?>