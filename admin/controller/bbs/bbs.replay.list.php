<?php
/**
* 评论列表控制器文件
*
* @version        $Id: operate.replay.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受主题id
$bid = Request('id');

if( $orderField == '' )
{
	$where['order'] = 'replay_id desc';
}

//获取列表条件
$where['table'] = '@replay_replay';
$where['where']['replay_module'] = 'bbs';
if( $bid != '' )
{
	$where['where']['replay_cid'] = $bid;
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$where['left']['@bbs_bbs as b'] = 'replay_cid=bbs_id';
$where['left']['@bbs_type as t'] = 'b.type_id=t.type_id';
$dataArr = wmsql::GetAll($where);
?>