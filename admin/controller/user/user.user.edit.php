<?php
/**
* 用户编辑控制器文件
*
* @version        $Id: user.user.edit.php 2016年5月5日 15:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userSer = AdminNewClass('user.user');
$userConfig = AdminInc('user');

$displayArr = $userSer->GetDisplay();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@user_user';
	$where['left']['@user_finance'] = 'finance_user_id=user_id';
	$where['where']['user_id'] = $id;

	$data = wmsql::GetOne($where);
}
//不存在就设置默认值
else
{
	$data['user_head'] = $userConfig['default_head'];
	$data['user_status'] = $userConfig['reg_status'];
	$data['user_sign'] = $userConfig['reg_sign'];
	$data['user_gold1'] = $userConfig['reg_gold1'];
	$data['user_gold2'] = $userConfig['reg_gold2'];
	$data['user_exp'] = $userConfig['reg_exp'];
	$data['user_display'] = $data['user_sex'] = 1;
	$data['user_browse'] = $data['user_topic'] = $data['user_retopic'] = $data['user_replay'] =
	$data['user_emailtrue'] = 0;
	$data['user_age'] = 18;
	$data['user_displaytime'] = $data['user_logintime'] = $data['user_regtime'] = time();
	$data['user_displaytime'] = @strtotime(0);
}
?>