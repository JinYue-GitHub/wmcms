<?php
/**
* 保存用户头像请求处理
*
* @version        $Id: savehead.php 2015年8月15日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月28日 14:37  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
//头像不能为空
$id = str::Int( Post('id/i') , $lang['user']['head_id_err']);
//先查询头像是否存在
$headMod = NewModel('user.head');
$head = $headMod->GetById($id);
if( !$head )
{
    ReturnData( $lang['user']['head_id_err'] , $ajax);
}
else
{
    $userMod->head = $head['head_src'];
    $result = $userMod->SaveHead(user::GetUid());
    ReturnData( $lang['user']['operate']['savehead']['success'] , $ajax , 200);
}
?>