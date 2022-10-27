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
$cid = str::Int( Request('cid') , $lang['dingcai']['cid_err'] );
$module = str::IsEmpty( Request('module') , $lang['dingcai']['module'] );
//获取能操作的模块
$moduleArr = GetModuleName('all.bbs.about.message.link.user.diy.down.zt');


//需要操作的模块是否存在
if( GetKey($moduleArr,$module) == '' )
{
	tpl::ErrInfo( $lang['dingcai']['module'] );
}
//顶踩操作是否正确
else if ( $type != 'ding' &&  $type != 'cai' )
{
	tpl::ErrInfo( $lang['dingcai']['type'] );
}
//操作的模块是否存在
else if( !$tableSer->GetTable($module) )
{
	tpl::ErrInfo( $lang['dingcai']['module_no'] );
}
else
{
	require_once 'dingcai.php';
}
?>