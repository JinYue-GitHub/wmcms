<?php
/**
* 自定义标签控制器文件
*
* @version        $Id: system.config.label.edit.php 2016年5月20日 21:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$configSer = AdminNewClass('system.config');
$configMod = NewModel('system.config');

//接受数据
$id = Get('id','0');
if( $type == '' ){$type = 'add';}

//所有模块分类
$moduleArr = $configMod->GetFieldModule();
$fromArr = $configSer->GetFromType();

//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@config_field';
	$where['where']['field_id'] = $id;
	
	$data = wmsql::GetOne($where);
	
	if( $data )
	{
		$wheresql['table'] = '@'.$data['field_module'].'_type';
		$wheresql['field'] = 'type_id,type_topid,type_name';
		$typeData = wmsql::GetAll($wheresql);
		if( !$typeData )
		{
			$data['type_list']['type_id'] = '';
			$data['type_list']['type_name'] = '对不起，当前模块没有分类';
		}
		else
		{
			$data['type_list'] = $typeData;
		}
		
		//循环分类
		foreach ($typeData as $k=>$v){
			if( $v['type_id'] == $data['field_type_id'])
			{
				$data['type_name'] = $v['type_name'];
				break;
			}
		}
		if( empty($data['type_name']) )
		{
			$data['type_name'] = '全部分类';
		}
	}
}
?>