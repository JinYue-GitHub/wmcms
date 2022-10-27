<?php
/**
* 财务日志处理器
*
* @version        $Id: finance.finance.php 2017年3月29日 22:04  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$logTable = '@user_finance_log';

//删除记录
if ( $type == 'del'  )
{
	$where['log_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了资金变更！' , 'finance' , 'delete' , $logTable , $where);
	wmsql::Delete($logTable , $where);
	
	Ajax('打赏记录批量删除成功!');
}
//清空数据记录
else if ( $type == 'clear')
{
	SetOpLog( '清空了所有资金变更记录！' , 'finance' , 'delete' , $logTable);
	wmsql::Delete($logTable);
	Ajax('资金变更全部清空成功！');
}
?>