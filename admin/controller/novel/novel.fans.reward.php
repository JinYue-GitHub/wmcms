<?php
/**
* 粉丝打赏记录控制器文件
*
* @version        $Id: novel.fans.reward.php 2017年3月29日 21:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$config = GetModuleConfig('user');

//接受post数据
$uid = Request('uid');
$cid = Request('cid');

//获取列表条件
$where['table'] = '@user_finance_log';
$where['field'] = '@user_finance_log.*,novel_name,user_nickname';
$where['left']['@novel_novel'] = 'log_cid=novel_id';
$where['left']['@user_user'] = 'log_user_id=user_id';
$where['where']['log_status'] = '2';
$where['where']['log_module'] = 'novel';
$where['where']['log_type'] = 'reward_consume';
if( $orderField == '' )
{
	$where['order'] = 'log_id desc';
}
//条件查询
if( $uid != '' )
{
	$where['where']['log_user_id'] = $uid;
}
if( $cid != '' )
{
	$where['where']['log_cid'] = $cid;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>