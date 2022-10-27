<?php
/**
* 错误日志处理器
*
* @version        $Id: system.safe.errlog.php 2016年4月23日 22:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_errlog';

//删除登录记录
if ( $type == 'del' )
{
	$where['errlog_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '删除了错误日志' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table, $where);
	Ajax('错误日志删除成功!');
}
//清空登录记录
else if ( $type == 'clear' )
{
	wmsql::Exec('TRUNCATE TABLE `@'.$table.'`');
	//写入操作记录
	SetOpLog( '清空了错误日志' , 'system' , 'delete');
	Ajax('所有错误日志成功清空！');
}
//处理自动上传设置
else if ( $type == 'autoupload' )
{
	$val = str::CheckElse(Request('val'), 1 , 1, 0);
	
	$where['config_module'] = 'system';
	$where['config_name'] = 'err_auto_upload';
	wmsql::Update( '@config_config' , array('config_value'=>$val) , $where);
	
	//写入操作记录
	SetOpLog( '从错误列表修改自动上传设置' , 'system' , 'update');
	Ajax('自动上传错误日志修改成功！');
}
//上传错误日志
else if ( $type == 'upload' )
{
	$where['table'] = $table;
	$where['where']['errlog_id'] = str::Int(Request('id'));
	$data = wmsql::GetOne($where);
	if( $data && $data['errlog_status'] == '1' )
	{
		Ajax('对不起，该错误日志已经上传过了！', 300);
	}
	else if( $data )
	{
		$cloudSer = NewClass('cloud');
		$rs = $cloudSer->ErrlogAdd($data);
		if( $rs['code'] == 200 )
		{
			//修改为已经上传状态
			wmsql::Update($table, array('errlog_status'=>1), array('errlog_id'=>$id));
			Ajax('错误日志上传成功！');
		}
		else
		{
			Ajax($rs['msg'], 300);
		}
	}
	else
	{
		Ajax('对不起，该错误日志不存在！' , 300);
	}
}
?>