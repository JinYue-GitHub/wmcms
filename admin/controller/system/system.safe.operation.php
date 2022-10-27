<?php
/**
* 管理员后台数据操作日志记录
*
* @version        $Id: system.safe.operation.php 2016年4月10日 14:42  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$safeSer = AdminNewClass('system.safe');

//获取列表条件
$where['table'] = '@manager_operation';
$where['field'] = '@manager_operation.*,manager_name';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where['left']['@manager_manager'] = 'operation_manager_id=manager_id';
$where = GetListWhere($where);

if( $where['order'] == '')
{
	$where['order'] = 'operation_id desc';
}

$operationArr = wmsql::GetAll($where);
?>