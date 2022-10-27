<?php
/**
* 卡号使用记录
*
* @version        $Id: user.card.log.php 2017年4月3日 19:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');
$cardMod = NewModel('user.card');
$cardArr = $cardMod->GetCardType();

//获取列表条件
$where['table'] = '@user_card_log';
$where['filed'] = '@user_card_log.*,user_nickname,card_key,card_type,card_card_channel';
$where['left']['@user_card'] = 'log_card_id=card_id';
$where['left']['@user_user'] = 'log_user_id=user_id';

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