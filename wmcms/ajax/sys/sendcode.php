<?php
/**
* 发送验证码
*
* @version        $Id: sendcode.php 2022年03月21日 15:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//信息类型id
$id = str::IsEmpty(Request('id'),$lang['system']['par']['err']);
//邮箱
$email = Request('email');
//手机号
$tel = Request('tel');
if( in_array($id,array('user_getpsw','user_bind')) )
{
    $codeType = !empty($email)?4:5;
    $codeOpen = 1;
}
else
{
    //验证码类型
    $codeType = C('config.web.'.$id.'_type');
    //验证码开启状态
    $codeOpen = C('config.web.'.$id);
}

if( $codeOpen != '1' )
{
	ReturnData($lang['sys']['sendcode_close'] , $ajax , 500);
}
//邮箱为空
else if( $codeType == '4' && !str::IsEmpty($email) )
{
	ReturnData($lang['sys']['sendcode_email_no'] , $ajax , 500);
}
//手机号为空
else if( $codeType == '5' && !str::IsEmpty($tel) )
{
	ReturnData($lang['sys']['sendcode_phone_no'] , $ajax , 500);
}
//正常
else if( $codeType == '4' || $codeType == '5' )
{
    $receive = $codeType=='4'?$email:$tel;
    $id = strtr($id,array('code_'=>''));
	//发送验证码
    $msgSer = NewClass('msg',array('type'=>$codeType,'id'=>$id));
    $result = $msgSer->SendCode($receive);
	ReturnData($result['msg'] , $ajax , $result['code']);
}
else
{
	ReturnData($lang['sys']['sendcode_type_no'] , $ajax , 500);
}