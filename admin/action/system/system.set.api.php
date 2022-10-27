<?php
/**
* 接口处理器
*
* @version        $Id: system.api.php 2016年3月28日 11:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//如果请求信息存在
if( $post )
{
	foreach ($post as $k=>$v)
	{
		$where = array();
		$data = array();
		$where['api_id'] = $k;
		$data['api_appid'] = $v['api_appid'];
		$data['api_apikey'] = $v['api_apikey'];
		$data['api_secretkey'] = $v['api_secretkey'];
		$data['api_open'] = $v['api_open'];
		if( isset($v['api_option']) )
		{
			$wheresql['table'] = '@api_api';
			$wheresql['field'] = 'api_option';
			$wheresql['where'] = $where;
			$apiData = wmsql::GetOne($wheresql);
			$option = unserialize($apiData['api_option']);
			foreach ($option as $key=>$val)
			{
				$option[$key]['value'] = $v['api_option'][$key];
			}
			$data['api_option'] = serialize($option);
		}
		$data = str::Escape($data , 'e');
		
		wmsql::Update('@api_api', $data, $where);
	}
	
	//写入操作记录
	SetOpLog( '修改API接口的配置信息' , 'system' , 'update' );

	
	//生成配置文件
	$apiSer = AdminNewClass('system.api');
	$apiSer->Update();
	
	Ajax();
}
?>