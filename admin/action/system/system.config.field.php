<?php
/**
* 自定义字段处理器
*
* @version        $Id: system.config.field.php 2016年9月21日 14:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@config_field';
$module = Request('module');

//修改配置信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = $post['field'];
	$where = $post['id'];
	$option = $post['option'];
	$options = array();

	if( $data['field_name'] == '' ||  GetKey($data,'field_type_id') == '' || GetKey($data,'field_module') == '' )
	{
		Ajax('对不起，所有项不能为空',300);
	}
	
	//循环option
	foreach ($option['formtype'] as $k=>$v)
	{
		if( $v != '' && $option['title'][$k] != '')
		{
			$optionArr['formtype'] = $v;
			$optionArr['title'] = $option['title'][$k];
			$optionArr['option'] = $option['option'][$k];
			$optionArr['default'] = $option['default'][$k];
			$options[] = $optionArr;
		}
	}
	//是否存在options
	if( !$options )
	{
		Ajax('对不起，字段选项必须填写一个！',300);
	}
	else
	{
		$data['field_option'] = serialize($options);
	}
	
	//字段名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['field_type'] = $data['field_type'];
	$wheresql['where']['field_module'] = $data['field_module'];
	$wheresql['where']['field_type_id'] = $data['field_type_id'];
	$wheresql['where']['field_id'] = array('<>',$where['field_id']);
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该模块的分类自定义字段已经存在！',300);
	}
	
	//如果子级分类有效就进行查询
	if( isset($data['field_type_child']) && $data['field_type_child'] == '1' && $data['field_type_id'] > '0')
	{
		$data['field_type_pids'] = GetTypePids($data['field_module'],$data['field_type_id']);
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['field_id'] = WMSql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了自定义字段'.$data['field_name'] , 'system' , 'insert' , $table , $where , $data );
		
		Ajax('自定义字段新增成功!');
	}
	//修改自定义字段
	else
	{
		//写入操作记录
		SetOpLog( '修改自定义字段' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		WMSql::Update($table, $data, $where);
		Ajax('自定义字段修改成功！');
	}
}
//删除自定义字段
else if ( $type == 'del' )
{
	$where['field_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了自定义字段' , 'system' , 'delete' , $table , $where);

	//删除标题
	wmsql::Delete($table, $where);
	//删除值
	wmsql::Delete('@config_field_value', array('value_field_id'=>$where['field_id']));
	
	Ajax('自定义字段删除成功!');
}
//根据模块获取分类
else if ( $type == 'gettype' )
{
	if( $module == '' )
	{
		$data[] = array('type_id'=>0,'type_topid'=>0,'type_pid'=>0,'type_name'=>'对不起，请选择模块');
		Ajax('获取成功' , 200 , $data);
	}
	else
	{
		$wheresql['table'] = '@'.$module.'_type';
		$wheresql['field'] = 'type_id,type_topid,type_pid,type_name';
		$wheresql['order'] = 'type_order';
		$data = wmsql::GetAll($wheresql);

		Ajax('获取成功' , 200 , $data);
	}
}
//根据模块和分类id获得自定义字段的内容
else if( $type == 'getfield')
{
	$conSer = AdminNewClass('system.config');
	
	$dataArr['module'] = $module;
	$dataArr['tid'] = Get('tid');
	$dataArr['pid'] = Get('pid');
	$dataArr['ft'] = Get('ft' , '1');
	$dataArr['cid'] = Get('cid' , '0');
	
	$fieldArr = $conSer->GetFieldOption($dataArr);

	if( $fieldArr )
	{
		foreach ($fieldArr as $k=>$v)
		{
			$fieldArr[$k]['form'] = $conSer->GetForm($v);
		}
	}
	Ajax('获取成功' , 200 , $fieldArr);
}
?>