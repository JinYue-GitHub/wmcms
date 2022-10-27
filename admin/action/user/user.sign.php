<?php
/**
* 用户处理器
*
* @version        $Id: user.sign.php 2016年5月11日 10:39  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@user_sign';

//删除数据
if ( $type == 'del')
{
	$where['user_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了用户签到记录' , 'user' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
	
	Ajax('用户签到记录删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了签到记录' , 'user' , 'delete');
	Ajax('所有签到记录成功清空！');
}
?>