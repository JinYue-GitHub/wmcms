<?php
/**
* 插件后台管理控制器分流
*
* @version        $Id: admin.php 2018年6月17日 10:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMMANAGER')){ exit("dont alone open!"); }
//设置插件的模板文件夹
tpl::$tempPath = WMPLUGIN.'apps/'.$m.'/templates/admin/';

//设置插件的根目录
$pluginRootPath = '/plugin/apps/'.$m.'/';
//自动引入的公共文件
$incList = array('config','common','function');
//插件入口文件
$file = $pluginRootPath.'/admin.php';

//入口文件不存在
if( !file_exists(WMROOT.$file) )
{
	die(tpl::Rep(array('文件路径'=>$file),$lang['system']['plugin']['file_err']));
}
else
{
	//验证插件是否安装了
	$pluginMod = NewModel('plugin.plugin');
	$pluginData = $pluginMod->GetByFloder($m);
	if( !$pluginData )
	{
		die($lang['system']['plugin']['no_install']);
	}
	else
	{
		//公共文件存在就自动引入
		foreach ($incList as $v)
		{
			if( file_exists(WMROOT.$pluginRootPath.'inc/'.$v.'.php') )
			{
				require_once WMROOT.$pluginRootPath.'inc/'.$v.'.php';
			}
		}

		//插件父类方法
		require_once WMPLUGIN.'plugin.class.php';
		plugin::SetData($pluginData);
		//引入插件主文件
		require_once WMROOT.$file;
		
		//插件类名是否存在
		if( !class_exists('WMCMSPluginAdmin') )
		{
			die($lang['system']['plugin']['class_err']);
		}
		else
		{
			//插件方法
			$WMCMSPlugin = new WMCMSPluginAdmin();
		}
		
		
		//自动为程序识别调用哪个方法
		$actionName = 'Action_'.$action;
		//将自定义的模板路径改为
		C('ua.path','web');
		
		//插件的指定UA本版方法是否存在
		if( method_exists($WMCMSPlugin , $actionName.'_'.C('ua.pt')) )
		{
			C('ua.path',GetPtMark(C('ua.pt_int')));
			$actionName = $actionName.'_'.C('ua.pt');
		}
		//如果版本方法存在，但是web版本存在就读取web版方法
		else if( method_exists($WMCMSPlugin , $actionName.'_web') )
		{
			$actionName = $actionName.'_web';
		}
		
		//如果所有方法不存在就提示错误
		if( !method_exists($WMCMSPlugin , $actionName) )
		{
			die(tpl::Rep(array('方法名'=>$actionName),$lang['system']['plugin']['action_no']));
		}
		else
		{
			//设置默认模板
			tpl::SetTemp($action);
			$WMCMSPlugin->$actionName();
		}

		//设置页面信息
		$pageArr = array('pagetype'=>'index','dtemp'=>tpl::GetTemp());
		if( C('page') )
		{
			$pageArr = array_merge(C('page'),$pageArr);
		}
		C('page',$pageArr);
		tpl::GetSeo();
		tpl::SetLabel('插件目录', $pluginRootPath);
		tpl::SetLabel('插件资源', $pluginRootPath.'/static/admin');
		
		//new一个模版类，然后输出
		$tpl=new tpl();
		$tpl->display();
	}
}
?>