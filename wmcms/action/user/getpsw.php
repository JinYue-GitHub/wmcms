<?php
/**
* 找回密码操作处理
*
* @version        $Id: getpsw.php 2016年5月29日 9:40  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

FormTokenCheck();
FormCodeCheck('code_user_getpsw');

$name = str::IsEmpty( Post('name') , $lang['user']['name_no']);
$receive = str::IsEmpty( Post('receive') , $lang['user']['receive_no']);
$smsCode = str::IsEmpty( Post('sms_code') , $lang['user']['sms_code_no']);

//账号长度和账号格式
str::CheckLen( $name , '4,16' , $lang['user']['name_len']  );
str::LN( $name, $lang['user']['name_err'] );


if( str::CheckEmail($receive) )
{
    $type = 'email';
    $where['user_email'] = $receive;
}
else if( str::CheckTel($receive) )
{
    $type = 'tel';
    $where['user_tel'] = $receive;
}
else
{
	ReturnData( $lang['user']['receive_err'] );
}
//查询账号
$where['user_name'] = $name;
$data = $userMod->GetOne($where);

//邮箱正确
if( $data )
{
	//邮箱没有经过验证，无法提供找回密码服务
	if( isset($where['user_email']) && $data['user_emailtrue'] =='0' )
	{
		ReturnData( $lang['user']['account_emailtrue'] );
	}
	//手机号没有经过验证，无法提供找回密码服务
	if( isset($where['user_tel']) && $data['user_teltrue'] =='0' )
	{
		ReturnData( $lang['user']['account_teltrue'] );
	}
	//检查验证码
    $msgSer = NewClass('msg',array('type'=>$type,'id'=>'user_getpsw'));
    $result = $msgSer->CheckCode($receive,array('code'=>$smsCode));
    if( $result['code'] != '200' )
    {
		ReturnData( $lang['user']['sms_code_err']);
    }
    else
    {
        //跳转到重置密码页面
        $result['url'] = '/module/user/repsw.php';
        $result['key'] = $userMod->GetPswKey($data);
    	FormDel();
    	//如果是ajax返回。
    	if ( $ajax )
    	{
    	    ReturnData( $lang['user']['operate']['getpsw']['success'] , $ajax, 200,$result);
    	}
    	//如果不是ajax请求就跳转到重置页面
    	else
    	{
    	    header("Location: ".$result['url'].'?key='.$result['key']);die();
    	}
    }
}
else
{
	ReturnData( $lang['user']['account_match_no'] );
}
?>