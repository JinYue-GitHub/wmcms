<?php
/**
* 应用资料处理器
*
* @version        $Id: app.attr.php 2016年5月15日 22:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @app           http://www.weimengcms.com
*
*/
$table = '@app_attr';

//修改应用资料
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['attr'], 'e' );
	$where = $post['id'];

	if ( $data['attr_name'] == '' )
	{
		Ajax('对不起，应用资料名必须填写！',300);
	}
	else if( $data['attr_type'] == '' )
	{
		Ajax('对不起，应用资料类型必须选择！',300);
	}

	//应用名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['attr_id'] = array('<>',$where['attr_id']);
	$wheresql['where']['attr_name'] = $data['attr_name'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该应用资料已经存在！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，应用资料添加成功！';
		$where['attr_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了应用资料'.$data['attr_name'] , 'app' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，应用资料修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了应用资料'.$data['attr_name'] , 'app' , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del' )
{
	$where['attr_id'] = GetDelId();
	wmsql::Delete($table,$where);

	SetOpLog( '删除了应用资料' , 'app' , 'delete' , $table , $where);
	Ajax('应用资料删除成功!');
}
?>