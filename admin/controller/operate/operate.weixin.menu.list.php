<?php
/**
* 微信自定义菜单列表控制器文件
*
* @version        $Id: operate.weixin.menu.list.php 2019年03月09日 13:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where = array();
$menuMod = NewModel('operate.weixin_menu');

if( $orderField == '' )
{
	$where['order'] = 'menu_id desc';
}

$aid = Request('aid/i');
$st = Request('st');
$name = Request('name');

//判断是否搜索标题
if( !empty($st) )
{
	//判断是否搜索标题
	if( !empty($name) )
	{
		if( $st == '1' )
		{
			$where['where']['menu_account_id'] = $name;
		}
		else if( $st == '2' )
		{
			$where['where']['account_name'] = array('like',$name);
		}
		else
		{
			$where['where']['menu_name'] = array('like',$name);
		}
	}
	else
	{
		$name = '请输入搜索关键字';
	}
}
else
{
	$st = 1;
	$name = '请输入搜索关键字';
}

//数据条数
$total = $menuMod->GetCount($where);
//当前页的数据
$dataArr = $menuMod->GetList(GetListWhere($where));
?>