<?php
/**
* 用户列表控制器文件
*
* @version        $Id: user.user.list.php 2016年5月5日 17:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$configArr = AdminInc('user');
$userSer = AdminNewClass('user.user');
$userMod = NewModel('user.user');
$punishMod = NewModel('user.punish');

//接受post数据
$name = Request('name');
$tel = Request('tel');
$email = Request('email');
$status = Request('status');
$userType = Request('user_type');


if( $orderField == '' )
{
	$where['order'] = 'user_id desc';
}

//获取列表条件
$where['table'] = '@user_user';

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['user_name'] = array('like',$name);
}
else
{
	$name = '请输入用户名';
}
//判断是否搜索手机号
if( $tel != '' )
{
	$where['where']['user_tel'] = array('like',$tel);
}
else
{
	$tel = '请输入用户手机号';
}
//判断是否搜索邮箱
if( $email != '' )
{
	$where['where']['user_email'] = array('like',$email);
}
else
{
	$email = '请输入用户邮箱';
}
//判断是否搜索状态
if( $status != '' )
{
	$where['where']['user_status'] = $status;
}
//判断是否搜索来源
if( $userType != '' )
{
	$where['where']['user_type'] = $userType;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@user_level'] = 'user_exp>=level_start and user_exp<level_end';
$dataArr = wmsql::GetAll($where);
?>