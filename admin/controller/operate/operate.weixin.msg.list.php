<?php
/**
* 微信公众号消息列表控制器文件
*
* @version        $Id: operate.weixin.msg.list.php 2019年03月10日 14:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where = array();
$msgMod = NewModel('operate.weixin_msg');

if( $orderField == '' )
{
	$where['order'] = 'msg_id desc';
}
$st = Request('st');
$et = Request('et');
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
			$where['where']['msg_content'] = array('like',$name);
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
//判断是否搜索类型
if( !empty($et) )
{
	$where['where']['msg_type'] = $et;
}
else
{
	$where['where']['msg_type'] = array('<>','event');
	$et = '';
}

//数据条数
$total = $msgMod->GetCount($where);
//当前页的数据
$dataArr = $msgMod->GetList(GetListWhere($where));
?>