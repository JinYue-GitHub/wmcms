<?php
/**
* 评论/回帖上传附件请求处理
*
* @version        $Id: replay.php 2017年7月8日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月28日 12:44  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//接受模块类型参数
$module = str::IsEmpty( Request('module') , $lang['upload']['replay_module_no'] );
//接受内容id参数
$cid = str::Int(Request( 'cid' ));
//获取能操作的模块
$moduleArr = GetModuleName('all.about.message.link.user.diy.down.zt');
//评论配置
$replayConfig = GetModuleConfig('replay');
//如果评论不是统一管理就读取模块评论设置
if ( $replayConfig['unify'] != '1' || $module == 'bbs')
{
	$config = GetModuleConfig($module);
	//开启分模块评论和登录
	$replayConfig['replay_open'] = GetKey($config,'replay_open');
	$replayConfig['replay_login'] = GetKey($config,'replay_login');
}

//需要操作的模块是否存在
if( GetKey($moduleArr,$module) == '' || !$tableSer->GetTable($module) )
{
	ReturnData( $lang['upload']['replay_module_no'] , $ajax );
}
//关闭了回帖。
else if ( $replayConfig['replay_open'] == '0' )
{
	ReturnData( $lang['upload']['replay_post_close'] , $ajax );
}
//需要登录或者是bbs模块
else if( ($replayConfig['replay_login'] == '1' && $uid <= 0) || ($module == 'bbs' && $uid == 0) )
{
	ReturnData( $lang['upload']['replay_no_login'] , $ajax );
}
//内容错误不存在。
else if($tableSer->GetCount($module,$cid) < 1)
{
	ReturnData( $lang['upload']['replay_par_err'] , $ajax );
}

//设置图片默认描述
$alt = $_FILES[$fileBtnName]['name'];
//如果不是图片设置保存文件夹
if( !str::IsImg($_FILES[$fileBtnName]['type']) )
{
	$filePath = $upPath.'files';
}
?>