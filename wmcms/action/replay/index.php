<?php
/**
* 全系统评论功能请求处理
*
* @version        $Id: index.php 2015年5月25日 21:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//接受参数
$module = str::IsEmpty( Request('module') , $lang['replay']['module'] );
//获取能操作的模块
$moduleArr = GetModuleName('all.about.message.link.user.diy.down');

//评论配置
require_once WMMODULE.'replay/replay.config.php';
//用户配置
require_once WMMODULE.'user/user.config.php';
//如果评论不是统一管理就读取模块评论设置
if ( $replayConfig['unify'] != '1' || $module == 'bbs')
{
	$config = GetModuleConfig($module);
	//开启分模块评论和登录
	$replayConfig['replay_open'] = GetKey($config,'replay_open');
	$replayConfig['replay_login'] = GetKey($config,'replay_login');
}
//需要操作的模块是否存在
if( GetKey($moduleArr,$module) == '' )
{
	tpl::ErrInfo( $lang['replay']['module'] );
}
//操作的模块是否存在
else if( !$tableSer->GetTable($module) )
{
	tpl::ErrInfo( $lang['replay']['module_no'] );
}
//判断方法文件是否存在
else if( !file_exists('replay/'.$type.'.php') )
{
	tpl::ErrInfo( $lang['replay']['type_err'] );
}
//引入操作文件
else
{
	require_once $type.'.php';
}
?>