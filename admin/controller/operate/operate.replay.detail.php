<?php
/**
* 评论详情
*
* @version        $Id: operate.replay.detail.php 2016年12月14日 19:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');
$data = '';

//如果id大于0
if ( str::Number($id) )
{
	//获取列表条件
	$where['table'] = '@replay_replay';
	$where['where']['replay_id'] = $id;
	$data = wmsql::GetOne($where);
	
	/*if ( $data )
	{
		$data['request_get'] = unserialize( $data['request_get'] );
		$data['request_post'] = unserialize( $data['request_post'] );
	}*/
}
?>