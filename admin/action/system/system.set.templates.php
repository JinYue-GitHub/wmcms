<?php
/**
* 模版管理处理器
*
* @version        $Id: system.templates.php 2016年3月30日 9:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');
$manager = AdminNewClass('manager');

//设置本处理器的表
$table = '@templates_templates';

//安装和卸载模版
if ( $type == 'install' || $type == 'uninstall')
{
	$path = Get('path');
	
	if( $path == '' )
	{
		Ajax('需要安装的模版文件夹不能为空！',300);
	}
	else if ( !file_exists('../templates/'.$path.'/copyright.xml') )
	{
		Ajax('对不起，模版版权信息不存在！',300);
	}
	else
	{		
		//查询模版是否安装了
		$where['table'] = $table;
		$where['where']['templates_path'] = $path;
		$tempArr = wmsql::GetOne($where);
		//获得模版版权信息
		$copyData = GetTempCopy( $path );
		
		//安装模版操作
		if( $type == 'install' )
		{
			//应用信息校正
			$rs = $cloudSer->APPInstall($path);
			if( $rs['code'] != '200' )
			{
				Ajax($rs['msg'],300);
			}
			else
			{
				//校正appid
				if( $rs['data']['online'] == '1' )
				{
					$copyData['appid'] = $rs['data']['appid'];
				}
				
				//查询模版信息是否正确
				if ( count($copyData) != '9' || $copyData['name'] == '' || $copyData['author'] == '')
				{
					Ajax('对不起，模版版权信息错误！',300);
				}
				//模版已经安装了
				else if ( $tempArr )
				{
					Ajax('对不起，模版已经安装了！',300);
				}
				else
				{
					$data['templates_path'] = $path;
					$data['templates_name'] = $copyData['name'];
					$data['templates_appid'] = $copyData['appid'];
					wmsql::Insert($table, $data);
					
					//写入操作记录
					SetOpLog( '安装了模版' , 'system' , 'update' );
					//更新模版配置文件
					UpTempConfig();
					Ajax('模版安装成功，可以使用了！');
				}
			}
		}
		//卸载模版操作
		else if( $type == 'uninstall' )
		{
			//模版没有安装
			if ( !$tempArr )
			{
				Ajax('对不起，模版没有安装无法卸载！',300);
			}
			else
			{
				wmsql::Delete($table, $where['where']);
				
				//更新模版配置文件
				UpTempConfig();

				//写入操作记录
				SetOpLog( '卸载了模版' , 'system' , 'update' );
				
				Ajax('模版卸载成功！');
			}
		}
	}
}
else
{
	//如果请求信息存在
	if( $post )
	{
		$configMod = NewModel('system.config');
		$configMod->UpdateToForm($post);

		//写入操作记录
		SetOpLog( '应用了默认模版' , 'system' , 'update' );
		
		//更新配置文件
		$manager->UpConfig('web');
		
		Ajax('默认模版更新成功！');
	}	
}

?>