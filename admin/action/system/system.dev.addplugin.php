<?php
/**
* 添加插件处理器
*
* @version        $Id: system.dev.addplugin.php.php 2019年10月27日 11:29  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$pluginMod = NewModel('plugin.plugin');
$pluginConfigMod = NewModel('plugin.config');
require_once WMPLUGIN.'plugin.class.php';

//如果请求信息存在
if( $type == 'plugin_add'  )
{
	$data = Post('data/a');
	$url = Post('url');
	$pluginPath = WMPLUGIN.'apps/'.$data['plugin_floder'];
	
	if( $data['plugin_name'] == '' || $data['plugin_floder'] == '' || 
		$data['plugin_author'] == '' || $data['plugin_version'] == '' )
	{
		Ajax('对不起，请填写完整插件信息！',300);
	}
	else if( $pluginMod->GetByFloder($data['plugin_floder']) || file_exists($pluginPath) )
	{
		Ajax('对不起，插件标识已被占用请更换！',300);
	}
	else
	{
		//插入插件信息
		if( $pluginMod->Insert($data) )
		{
			//创建插件文件夹
			file::CreateFolder($pluginPath);
			//将插件模版复制到模版文件夹
			file::CopyFile(WMTEMPLATE.'system/plugin', $pluginPath,'create.zip');
			//解压缩到当前文件夹下面
			$zip = NewClass('pclzip',$pluginPath.'/create.zip');
			$zip->extract(PCLZIP_OPT_PATH, $pluginPath);
			//删除模版文件
			file::DelFile($pluginPath.'/create.zip');
			//替换版权文件的占位标签
			$copyRight = file::GetFile($pluginPath.'/copyright.xml');
			$arr = array(
				'name'=>$data['plugin_name'],
				'author'=>$data['plugin_floder'],
				'ver'=>$data['plugin_author'],
				'time'=>$data['plugin_version'],
				'url'=>$url,
			);
			$copyRight = tpl::Rep($arr,$copyRight);
			file_put_contents($pluginPath.'/copyright.xml', $copyRight);

			//写入操作记录
			SetOpLog( '新增了插件'.$data['plugin_name'].'：'.$data['plugin_floder'] , 'system' , 'insert' );
			Ajax();
		}
		else
		{
			Ajax('对不起，插件添加失败！',300);
		}
	}
}
else if( $type == 'config_add' )
{
	$pluginId = intval(Post('config_plugin_id'));
	$configKey = Post('config_key');
	$configVal = Post('config_val');
	//插件数据
	$pluginData = $pluginMod->GetById($pluginId);
	if( !$pluginData )
	{
		Ajax('对不起，插件id错误！',300);
	}
	else if( $configKey == '' )
	{
		Ajax('对不起，请填写页面标识和页面名字！',300);
	}
	else
	{
		Plugin::SetData($pluginData);
		if( Plugin::AddConfig($configKey, $configVal) )
		{
			//写入操作记录
			SetOpLog( '添加了插件'.$pluginData['plugin_name'].'的配置'.$configKey , 'system' , 'insert' );
			Ajax('插件配置添加成功！');
		}
		else
		{
			Ajax('对不起，配置添加失败！',300);
		}
	}
}
else if( $type == 'getpluginconfig' )
{
	$pluginData = $pluginMod->GetById(intval(Request('id')));
	if( $pluginData )
	{
		Plugin::SetData($pluginData);
		$newData = ToEasyJson(plugin::GetConfigList() , 'config_key' , 'config_key');
		Ajax(null , null , $newData);
	}
	else
	{
		Ajax('',200);
	}
}
else if( $type == 'config_del' )
{
	$configKey = Post('config_key');
	$configPluginId = Post('config_plugin_id');
	$pluginData = $pluginMod->GetById(intval($configPluginId));
	if( $pluginData )
	{
		if( $pluginConfigMod->Delete($configPluginId,$configKey) )
		{
			//写入操作记录
			SetOpLog( '删除了插件'.$pluginData['plugin_name'].'的配置'.$configKey , 'system' , 'insert' );
			Ajax('插件配置删除成功！');
		}
		else
		{
			Ajax('对不起，配置删除失败！',300);
		}
	}
	else
	{
		Ajax('对不起，插件id不存在',300);
	}
}
?>