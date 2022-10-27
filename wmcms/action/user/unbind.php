<?php
/**
* 取消账号绑定
*
* @version        $Id: unbind.php 2022年03月27日 13:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$uid = user::GetUid();
$api = str::IsEmpty( Request('api') , $lang['user']['unbind_api_no'] );
//是否登录了
str::EQ( $uid, 0 , $lang['user']['no_login'] );

//获得当前用户绑定的第三方登录
$apiloginMod = NewModel('user.apilogin');
$data = $apiloginMod->GetByUid($uid,$api);
if( !$data )
{
    ReturnData( $lang['user']['unbind_api_no'] , $ajax);
}
else if( $apiloginMod->UnBind($uid,$api) )
{
    ReturnData( $lang['user']['operate']['unbind']['success'] , $ajax , 200);
}
else
{
    ReturnData( $lang['user']['operate']['unbind']['fail'] , $ajax);
}
?>