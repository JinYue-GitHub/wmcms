<?php
/**
* 全系统收藏验证处理器
*
* @version        $Id: index.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月25日 20:42  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//接受参数
$cid = str::Int( Request('cid') , $lang['coll']['cid_err'] );
$module = str::IsEmpty( Request('module') , $lang['coll']['module'] );

//获取能操作的模块
$moduleArr = GetModuleName('all.about.message.link.user.diy.down.zt');

//设置互动操作的类型
$setArr = array('coll','shelf','dingyue');

//需要操作的模块是否存在
if( GetKey($moduleArr,$module) == '' )
{
	tpl::ErrInfo( $lang['coll']['module'] );
}
//操作类型是否正确
else if ( !in_array($type, $setArr) )
{
	tpl::ErrInfo( $lang['coll']['type'] );
}
//操作的模块是否存在
else if( !$tableSer->GetTable($module) )
{
	tpl::ErrInfo( $lang['coll']['module_no'] );
}
else
{
	require_once 'coll.php';
}
?>