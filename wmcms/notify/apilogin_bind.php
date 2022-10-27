<?php
/**
* 登陆后绑定账号操作
*
* @version        $Id: apilogin_bind.php 2022年03月27日 11:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }
if( $loginData )
{
    ReturnData( $lang['user']['api_bind_exist'] , $ajax );    
}
else
{
    if( $userMod->InsertApiLogin(user::GetUid() , $userInfo) )
    {
    	//清除api登录数据
    	ClearApiLogin();
        header("Location:".tpl::url('user_bind'));
    }
    ReturnData( $lang['user']['api_bind_err'] , $ajax );    
}
?>