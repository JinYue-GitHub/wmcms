<?php
/**
* 卡号列表
*
* @version        $Id: user.card.list.php 2016年4月6日 15:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');
$cardMod = NewModel('user.card');
$cardArr = $cardMod->GetCardType();

$cType = Request('ctype');
$use = Request('use');
$card = Request('card');

//是否搜索关键字
if( $card != '' )
{
	$where['where']['card_key'] = array('like',$card);
}
else
{
	$card = '请输入卡号关键字';
}
//卡号类型条件
if( $cType != '' )
{
	$where['where']['card_type'] = $cType;
}
//是有使用条件
if( $use != '' )
{
	$where['where']['card_use'] = $use;
}

//获取列表条件
$where['table'] = '@user_card';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);

if( $where['order'] == '')
{
	$where['order'] = 'card_id desc';
}
$cardList = wmsql::GetAll($where);
?>