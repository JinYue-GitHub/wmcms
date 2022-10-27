<?php
/**
* 编辑微信自定义菜单控制器文件
*
* @version        $Id: operate.weixin.menu.edit.php 2019年03月09日 13:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$menuMod = NewModel('operate.weixin_menu');
$accountMod = NewModel('operate.weixin_account');

//接受数据
$id = Get('id');
$aid = Get('aid/i');
$aName = '';
if( $type == '' ){$type = 'add';}

$accountList = $accountMod->GetList();
//如果id大于0
if ( $type == 'edit')
{
	$data = $menuMod->GetById($id);
	if( $data )
	{
		$data['menu_updatetime'] = date('Y-m-d H:i:s',$data['menu_updatetime']);
		$data['menu_pushtime'] = date('Y-m-d H:i:s',$data['menu_pushtime']);
	}
}
//不存在就设置默认值
else
{
	$data['menu_updatetime'] = '------';
	$data['menu_pushtime'] = '------';
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