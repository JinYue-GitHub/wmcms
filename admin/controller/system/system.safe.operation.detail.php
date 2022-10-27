<?php
/**
* 操作记录详情控制器
*
* @version        $Id: system.safe.operation.detail.php 2016年4月10日 15:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$safeSer = AdminNewClass('system.safe');

//接受数据
$id = Get('id');
$data = '';

//如果id大于0
if ( str::Number($id) )
{
	//获取列表条件
	$where['table'] = '@manager_operation';
	$where['field'] = '@manager_operation.*,manager_name';
	$where['left']['@manager_manager'] = 'operation_manager_id=manager_id';
	$where['where']['operation_id'] = $id;
	$data = wmsql::GetOne($where);
	
	if ( $data )
	{
		$data['operation_module'] = $safeSer->GetModuleName( $data['operation_module'] );
		$data['operation_type'] = $safeSer->GetOperaName( $data['operation_type'] );
		$data['operation_where'] = unserialize($data['operation_where']);
		$data['operation_data'] = unserialize($data['operation_data']);

		//如果镜像数据不为空则进行范序列化
		if( $data['operation_backdata'] != '' )
		{
			$data['operation_backdata'] = unserialize($data['operation_backdata']);
		}
		

		$operation = $safeSer->GetOpData( $data['operation_where'] , $data['operation_data'] );
	}
}
?>