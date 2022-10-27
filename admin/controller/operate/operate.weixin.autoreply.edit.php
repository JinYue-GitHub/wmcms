<?php
/**
* 编辑微信自动回复控制器文件
*
* @version        $Id: operate.weixin.autoreply.edit.php 2019年03月09日 14:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$replyMod = NewModel('operate.weixin_autoreply');
$accountMod = NewModel('operate.weixin_account');

//接受数据
$id = Get('id/i');
$aid = Get('aid/i');
$aName = '';
if( $type == '' ){$type = 'add';}

$accountList = $accountMod->GetList();
//如果id大于0
if ( $type == 'edit')
{
	$data = $replyMod->GetById($id);
}
//不存在就设置默认值
else
{
	$data['autoreply_type'] = 'text';
}

//查找是否存在公众号
if ( $accountList )
{
	foreach ($accountList as $k=>$v)
	{
		if( $v['account_id'] == $aid )
		{
			$aName = $v['account_name'];
			break;
		}
	}
	
}
?>