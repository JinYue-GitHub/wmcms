<?php
/**
* 搜索跳转处理模块
*
* @version        $Id: search.php 2015年9月2日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月26日 14:18 weimeng
*
*/
//引入模块公共文件
require_once 'search.common.php';

//参数判断
$module = str::IsEmpty( Request('module') , $lang['serach']['par']['module_no'] );
$key = str::IsEmpty( Request('key') , $lang['serach']['par']['key_no'] );
$type = str::In( Request('type') , array(array('0','1','2','3'),1) );
//进行utf-8转码
$key = str::EnCoding($key);
$url = tpl::Url( $module.'_search' , array('key'=>urlencode($key),'type'=>$type,'page'=>1) );

//跳转到相应的模块
if( $url == '' )
{
	header("Location: /index.php");
}
else
{
	header("Location: ".$url);
}
exit;
?>