<?php
/**
* 注册账号操作处理
*
* @version        $Id: reg.php 2016年5月29日 9:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//判断是否开启注册
str::EQ( $userConfig['reg_open'], 0, $lang['user']['reg_close']);

//接受参数
$name = str::IsEmpty( Post('name') , $lang['user']['name_no'] );
$psw = str::IsEmpty( Post('psw') , $lang['user']['psw_no'] );
$repsw = str::IsEmpty( Post('repsw') , $lang['user']['repsw_no'] );
$sex = str::Int( Post('sex') , null , 1);
$email = Post('email');
$tel = Post('tel');
$receive = '';

//账号长度和账号格式
str::LN( $name, $lang['user']['name_err'] );
str::CheckLen( $name , '4,30' , $lang['user']['name_len']  );
//密码长度和密码格式
str::CheckLen( $psw , '6,16' , $lang['user']['psw_len']  );
str::NCN( $psw, $lang['user']['psw_err'] );
//两次密码是否相等
str::NEQ( $psw, $repsw , $lang['user']['psw_repsw'] );
//查询账号是否被注册
user::CheckName( $name , $lang['user']['name_exist'] );

$apiUser = unserialize(str::Encrypt(Post('apiuser'),'D'));
$apiReg = 1;
if( !is_array($apiUser) )
{
	$apiReg = 0;
	FormTokenCheck();
    //如果不是api注册
    if( C('config.web.code_user_reg')=='1' )
    {
        //检查邮箱
        if( C('config.web.code_user_reg_type') == '4' )
        {
            str::CheckEmail( $email , $lang['user']['email_err'] );
        	user::CheckEmail( $email , $lang['user']['email_exist'] );
        	FormCodeCheck('code_user_reg',false,$email);
        	$receive = $email;
            $data['email'] = $email;
            $data['emailtrue'] = 1;
        }
        //检查手机号
        else if( C('config.web.code_user_reg_type') == '5' )
        {
            str::CheckTel( $tel , $lang['user']['tel_err'] );
        	user::CheckTel( $tel , $lang['user']['tel_exist'] );
        	FormCodeCheck('code_user_reg',false,$tel);
        	$receive = $email;
            $data['tel'] = $tel;
            $data['teltrue'] = 1;
        }
        //普通验证
        else
        {
            FormCodeCheck('code_user_reg');
        }
    }
}
//设置数据
$userMod = NewModel('user.user');
$data['name'] = $name;
$data['psw'] = $psw;
$data['salt'] = str::GetSalt();
$data['sex'] = $sex;
$data['api'] = $apiReg;
$data['api_user'] = $apiUser;

if( isset($apiUser['api']) )
{
	$data['type'] = $apiUser['api'];
}
//插入数据
$result = $userMod->Reg($data);

//如果插入成功
if( $result )
{
	//模拟登录,如果是正常状态
	if ( $userConfig['reg_status'] == '1' )
	{
		Cookie('user_account' , str::A($name, str::E($psw,$data['salt'])));
	}
	
	//发送欢迎邮件或者短信
    $msgSer = NewClass('msg',array('type'=>C('config.web.code_user_reg_type'),'id'=>'user_reg'));
    $msgSer->SendCode($receive);

	$info = GetInfo($lang['user']['operate']['reg'] , 'user_home');	
	$code = 200;
	
	//表单token删除
	FormDel();
	//处理返回用户信息
	$data = ProcessReturnUser($userMod->GetOne($result));
}
else
{
	$data = '';
	$info = $lang['user']['operate']['reg']['fail'];
	$code = 500;
}
//返回提示
ReturnData( $info , $ajax , $code , $data);
?>