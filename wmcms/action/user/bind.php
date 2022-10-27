<?php
/**
* 绑定账号操作处理
*
* @version        $Id: reg.php 2016年5月29日 9:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

//接受参数
$email = Post('email');
$tel = Post('tel');
$code = Post('code');

//检查邮箱
if( !empty($email) )
{
    str::CheckEmail( $email , $lang['user']['email_err'] );
	user::CheckEmail( $email , $lang['user']['email_auth'] );
	$receive = $email;
	$type = 'email'; 
}
//检查手机号
else
{
    str::CheckTel( $tel , $lang['user']['tel_err'] );
	user::CheckTel( $tel , $lang['user']['tel_auth'] );
	$receive = $tel;
	$type = 'tel'; 
}

//检查验证码
$msgSer = NewClass('msg',array('type'=>$type,'id'=>'user_bind'));
$result = $msgSer->CheckCode($receive,array('code'=>$code));
if( $result['code'] != '200' )
{
	ReturnData( $lang['user']['sms_code_err']);
}
else
{
    //保存数据
    $userMod = NewModel('user.user');
    $result = $userMod->SaveAuthTrue(user::GetUid(),$type,$receive);
    //如果修改成功
    if( $result )
    {
        ReturnData( $lang['user']['operate']['bind']['success'] , $ajax , 200);
    }
    else
    {
        ReturnData( $lang['user']['operate']['bind']['fail'],$ajax);
    }
}
?>