<?php
/**
* 网站配置处理器
*
* @version        $Id: system.config.config.php 2016年4月23日 11:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@config_config';

//修改配置信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape($post['data'] , 'e');
	$data['config_value'] = str::Escape($data['config_value'] , 'e');
	$where = Post('id/a');

	//如果是新增
	if ( $type == 'add' )
	{
		//查询是否存在
		$wheresql['table'] = $table;
		$wheresql['where']['group_id'] = $data['group_id'];
		$wheresql['where']['config_module'] = $data['config_module'];
		$wheresql['where']['config_name'] = $data['config_name'];
		
		if ( wmsql::GetCount($wheresql) > 0 )
		{
			Ajax('对不起，当前分组已经存在同名配置' , 300);
		}
		else
		{
			//插入记录
			$where['config_id'] = WMSql::Insert($table, $data);
			
			//写入操作记录
			SetOpLog( '新增了网站配置字段'.$data['config_title'] , 'system' , 'insert' , $table , $where , $data );
			
			Ajax('网站配置字段新增成功!');
		}
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改网站配置字段' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		WMSql::Update($table, $data, $where);
		Ajax('网站配置字段修改成功！');
	}
}
//删除网站配置
else if ( $type == 'del' )
{
	$where['config_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了网站配置项' , 'system' , 'delete' , $table , $where);

	wmsql::Delete($table, $where);
	
	Ajax('网站配置项删除成功!');
}
//根据传入的配置分组id查询当前分组id下面的所有配置
if( $type == 'getconfig' )
{
	$id = Get('id');
	if ( $id != '' )
	{
		$where['table'] = $table;
		$where['where']['group_id'] = $id;
		$where['where']['config_formtype'] = array('or','radio,select');
		$where['order'] = 'config_order';
		$data = WMSql::GetAll($where);

		$newData = ToEasyJson($data , 'config_id' , 'config_title');
		
		Ajax(null , null , $newData);
	}
}
//根据传入的配置分组id查询当前分组id下面的所有配置
if( $type == 'update' )
{
	//生成api
	$apiSer = AdminNewClass('system.api');
	$apiSer->Update();

	$manager = AdminNewClass('manager');
	//更新网站配置文件
	$manager->UpConfig('web');
	//更新路由配置文件
	$manager->UpConfig('route');
	
	//站群配置
	$siteSer = AdminNewClass('system.site');
	$siteSer->UpConfig('site');
	
	//seo配置
	$seoSer = AdminNewClass('system.seo');
	$seoSer->UpConfig();
	
	Ajax('配置生成成功！');
}
?>