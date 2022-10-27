<?php
/**
* 自定义标签处理器
*
* @version        $Id: system.config.label.php 2016年5月20日 21:50  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@config_label';

//修改配置信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape($post['label'] , 'e');
	$data['label_value'] = str::Escape($data['label_value'] , 'e');
	$where = Post('id/a');
	
	if( $data['label_title'] == '' ||  $data['label_name'] == ''  ||  $data['label_value'] == '' )
	{
		Ajax('对不起，所有项不能为空',300);
	}
	
	//标签名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['label_name'] = $data['label_name'];
	$wheresql['where']['label_id'] = array('<>',$where['label_id']);
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该标签已经存在！',300);
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['label_id'] = WMSql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了自定义标签'.$data['label_title'] , 'system' , 'insert' , $table , $where , $data );
		
		Ajax('自定义标签新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改自定义标签' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		WMSql::Update($table, $data, $where);
		Ajax('自定义标签修改成功！');
	}
}
//删除网站配置
else if ( $type == 'del' )
{
	$where['label_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了自定义标签' , 'system' , 'delete' , $table , $where);

	wmsql::Delete($table, $where);
	
	Ajax('自定义标签删除成功!');
}
?>