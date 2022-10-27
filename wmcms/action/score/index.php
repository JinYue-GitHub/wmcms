<?php
/**
* 全系统顶踩功能参数验证处理器
*
* @version        $Id: index.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月23日 18:21  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//接受参数
$cid = str::Int( Request('cid') , $lang['score']['cid_err'] );
$module = str::IsEmpty( Request('module') , $lang['score']['module'] );
$score = str::Int( Request('score') , $lang['score']['score_err'] );

//获取能操作的模块
$moduleArr = GetModuleName('all.about.message.link.user.diy.down.zt');

//获得模块的评分设置
$config = GetModuleConfig($module);


//操作的模块是否存在
if( !$tableSer->GetTable($module) )
{
	tpl::ErrInfo( $lang['dingcai']['module_no'] );
}
else
{
	require_once 'score.php';
}
?>