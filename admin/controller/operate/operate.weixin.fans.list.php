<?php
/**
* 微信公众号粉丝控制器文件
*
* @version        $Id: operate.weixin.fans.list.php 2019年03月12日 23:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where = array();
$fansMod = NewModel('operate.weixin_fans');

if( $orderField == '' )
{
	$where['order'] = 'fans_id desc';
}

$name = Request('name');
$st = Request('st');

//判断是否搜索标题
if( !empty($st) )
{
	//判断是否搜索标题
	if( !empty($name) )
	{
		if( $st == '1' )
		{
			$where['where']['fans_account_id'] = $name;
		}
		else if( $st == '2' )
		{
			$where['where']['fans_nickname'] = array('like',$name);
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
$total = $fansMod->GetCount($where);
//当前页的数据
$dataArr = $fansMod->GetList(GetListWhere($where));
?>