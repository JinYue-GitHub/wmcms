<?php
/**
* 编辑微信公众号控制器文件
*
* @version        $Id: operate.weixin.account.edit.php 2019年03月08日 22:28  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$accountMod = NewModel('operate.weixin_account');

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $accountMod->GetById($id);
	if( $data )
	{
		$data['account_access'] = $accountMod->access[$data['account_access']];
	}
}
//不存在就设置默认值
else
{
	$data['account_token'] = '添加后系统自动生成';
	$data['account_aeskey'] = '添加后系统自动生成';
	$data['account_access'] = $accountMod->access[0];
}
?>