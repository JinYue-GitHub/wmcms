<?php
/**
* 错误如日志记录详情
*
* @version        $Id: system.safe.errlog.detail.php 2016年4月24日 10:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');

//如果id大于0
if ( str::Number($id) )
{
	$where['table'] = '@system_errlog';
	$where['where']['errlog_id'] = $id;

	$data = wmsql::GetOne($where);
}
?>