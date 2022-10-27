<?php
/**
* 错误日志控制器
*
* @version        $Id: system.safe.errlog.php 2016年4月23日 23:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//获取列表条件
$where['table'] = '@system_errlog';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);

if( $where['order'] == '')
{
	$where['order'] = 'errlog_id desc';
}
$errlogArr = wmsql::GetAll($where);
?>