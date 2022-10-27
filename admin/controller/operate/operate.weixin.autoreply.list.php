<?php
/**
* 微信自动回复控制器文件
*
* @version        $Id: operate.weixin.autoreply.list.php 2019年03月09日 14:53  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where = array();
$replyMod = NewModel('operate.weixin_autoreply');

if( $orderField == '' )
{
	$where['order'] = 'autoreply_id desc';
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
			$where['where']['autoreply_account_id'] = $name;
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
$total = $replyMod->GetCount($where);
//当前页的数据
$dataArr = $replyMod->GetList(GetListWhere($where));
?>