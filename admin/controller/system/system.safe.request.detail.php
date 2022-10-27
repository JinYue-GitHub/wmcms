<?php
/**
* 请求日志记录详情
*
* @version        $Id: system.safe.request.detail.php 2016年4月9日 16:22  weimeng
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
	$where['table'] = '@manager_request';
	$where['field'] = '@manager_request.*,manager_name';
	$where['left']['@manager_manager'] = 'request_manager_id=manager_id';
	$where['where']['request_id'] = $id;
	$data = wmsql::GetOne($where);
	
	if ( $data )
	{
		$data['request_get'] = unserialize( $data['request_get'] );
		$data['request_post'] = unserialize( $data['request_post'] );
	}
}
?>