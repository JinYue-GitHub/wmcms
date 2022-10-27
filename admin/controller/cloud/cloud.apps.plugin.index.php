<?php
/**
* 插件管理控制器
*
* @version        $Id: cloud.apps.plugin.manager.php 2018年6月12日 21:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//管理的插件id
$id = Request('id');
$pluginData = array();

if( !str::Number($id) )
{
	$errInfo = '对不起，插件id错误！';
}
else
{
	//查询已安装的插件
	$pluginMod = NewModel('plugin.plugin');
	$pluginData = $pluginMod->GetById($id);
	if( !$pluginData )
	{
		$errInfo = '对不起，插件不存在！';
	}
	else
	{
		//插件默认入口方法
		$defaultAction = 'index';
		//配置文件路径
		$pluginConfigFile = WMPLUGIN.'apps/'.$pluginData['plugin_floder'].'/inc/config.php';
		//插件的二维码文件
		$qrcodeFile = WMPLUGIN.'apps/'.$pluginData['plugin_floder'].'/qrcode.png';
		//二维码的相对路径
		$qrcodePath = '/plugin/apps/'.$pluginData['plugin_floder'].'/qrcode.png';
		
		//如果配置文件存在就引入
		if( file_exists($pluginConfigFile) )
		{
			require_once $pluginConfigFile;
			//如果存在默认入口方法就设置为默认的
			if( C('plugin.demo.default_action') != '' )
			{
				$defaultAction = C('plugin.demo.default_action');
			}
		}
		//默认首页地址
		$pluginIndexUrl = Url('plugin;'.$pluginData['plugin_floder'].'.index.'.$defaultAction.';',true);
		

		//首页二维码如果不存在就生成二维码
		if( !file_exists($qrcodeFile) )
		{
			$qrCodeSer = NewClass('qrcode');
			$qrCodeSer->png($pluginIndexUrl,$qrcodeFile,'L',5,2);
		}
	}
}
?>