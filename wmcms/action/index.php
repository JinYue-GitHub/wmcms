<?php
/**
* 全系统功能请求处理分解控制器
*
* @version        $Id: index.php 2016年5月23日 15:22  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2016年11月17日 21:27 weimeng
*
*/
//载入基本类文件
$C['module']['inc']['class'] = array('str','page','file','img');
$C['module']['inc']['module'] = array('all');

//引入公共文件
$siteCache = false;
require_once '../inc/common.inc.php';

//接受参数
$ajax = str::IsTrue( Request('ajax') , 'yes' , 'page.ajax');
$action = str::IsEmpty( Request('action') , $lang['system']['action']['no_action'] );

/**
 * 获得提示信息的数组
 * @param 参数1，提示数组
 * @param 参数2，url名
 * @param 参数3，url的参数替换
 */
function GetInfo($infoArr , $urlName = '', $urlPar = '')
{
	$url = tpl::Url($urlName , $urlPar);
	$info['info'] = $infoArr['success'];
	$info['gourl'] = $url;
	$info['html'] = tpl::Rep(array('url'=>$url) , $infoArr['html']);
	return $info;
}

//对方法参数进行分割，判断
$aArr = explode('.',str::ClearInclude($action));
if( count($aArr) > 1 )
{
	$action = $aArr[0];
	$type = $aArr[1];
	//键为需要替换的字符，值为替换后的字符
	if( isset($aArr[2]) )
	{
		$type .= '.'.$aArr[2];
	}
	

	if( file_exists($action.'/index.php') && 
		(file_exists($action.'/'.$type.'.php') || file_exists($action.'/'.$action.'.php')) )
	{
		//运行主方法事件
		$runMainAction = true;
		//引入方法文件
		//语言包
		$langPath = $action.'/lang/'.$C['config']['web']['lang'].'/system.php';
		if( file_exists($langPath) )
		{
			require_once $langPath;
		}
		//保存模块公共属性
		C('page.module','action');
		C('page.action_path',$action);
		C('page.action_file',$type);
		//获得插件的钩子
		$hookList = GetPluginHook('action.'.$action.'.'.$type);
		C('page.hook_list',$hookList);
		//使用钩子前置方法
		if( !empty($hookList['before']) )
		{
			foreach( $hookList['before'] as $k=>$v)
			{
				if( file_exists(WMPLUGIN.'/apps/'.$k.'/hook/action/'.$action.'/'.$type.'.before.php') )
				{
					require_once WMPLUGIN.'/apps/'.$k.'/hook/action/'.$action.'/'.$type.'.before.php';
				}
			}
		}
		//事件前置方法
		require_once 'index.before.php';
		//是否运行主事件
		if( $runMainAction == true )
		{
			require_once $action.'/index.php';
		}
	}
	//方法文件不存在
	else
	{
		tpl::ErrInfo($lang['system']['action']['no_file']);
	}
}
//方法参数错误
else
{
	tpl::ErrInfo($lang['system']['action']['no_action']);
}
?>