<?php
/**
* 管理员请求日志记录
*
* @version        $Id: system.safe.request.php 2016年4月9日 15:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//获取列表条件
$where['table'] = '@manager_request';
$where['field'] = '@manager_request.*,manager_name';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where['left']['@manager_manager'] = 'request_manager_id=manager_id';
$where = GetListWhere($where);

if( $where['order'] == '')
{
	$where['order'] = 'request_id desc';
}

$requestArr = wmsql::GetAll($where);
?>