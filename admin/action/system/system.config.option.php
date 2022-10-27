<?php
/**
* 网站配置选项处理器
*
* @version        $Id: system.config.option.php 2016年4月23日 17:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@config_option';

//修改网站配置选项
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape($post['data'], 'e');
	$where = Post('id/a');
	
	//如果是新增
	if ( GetKey($where,'option_id') == '' || $type == 'add' )
	{
		if( $data['config_id'] == '' )
		{
			Ajax('对不起，请选择所属配置！',300);	
		}
		
		//插入记录
		$where['option_id'] = WMSql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了网站配置选项'.$data['option_title'] , 'system' , 'insert' , $table , $where , $data );
		
		Ajax('网站配置字段新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改网站配置选项' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		WMSql::Update($table, $data, $where);
		Ajax('网站配置选项修改成功！');
	}
}
//删除网站配置
else if ( $type == 'del' )
{
	$where['option_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了网站配置选项' , 'system' , 'delete' , $table , $where);

	wmsql::Delete($table, $where);
	
	Ajax('网站配置选项删除成功!');
}
?>