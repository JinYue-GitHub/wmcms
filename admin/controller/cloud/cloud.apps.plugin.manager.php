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
$cFun = $cFun.'_'.$id;

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
		$pluginMenu = array();
		$pluginSystemMenu = array(
			'system'=>array(
				'name'=>'系统菜单',
				'menu'=>array(array('name'=>'插件首页','action'=>'index')),
			),
		);
		
		//如果存在目录配置就加载目录文件
		$menuFile = WMPLUGIN.'apps/'.$pluginData['plugin_floder'].'/inc/menu.php';
		if( file_exists($menuFile) )
		{
			require_once $menuFile;
			if( empty($pluginMenu) )
			{
				$pluginMenu = $pluginSystemMenu;
			}
			else
			{
				if( isset($pluginMenu['system']) )
				{
					array_unshift($pluginMenu['system']['menu'], $pluginSystemMenu['system']['menu'][0]);
				}
				else
				{
					array_unshift($pluginMenu['system']['menu'], $pluginSystemMenu);
				}
			}
		}
		else
		{
			$pluginMenu = $pluginSystemMenu;
		}
		
		//循环设置目录的url
		foreach ($pluginMenu as $key=>$val)
		{
			foreach ($val['menu'] as $k=>$v)
			{
				if( $v['action'] == 'index' )
				{
					$url = 'index.php?d=yes&c=cloud.apps.plugin.index&t=index&id='.$id;
				}
				else
				{
					$url = 'index.php?d=yes&c=cloud.apps.plugin.business&t=business&id='.$id.'&action='.$v['action'];
				}
				$pluginMenu[$key]['menu'][$k]['action'] = $url;
			}
		}
	}
}
?>