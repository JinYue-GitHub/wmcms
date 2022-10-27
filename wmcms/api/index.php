<?php
/**
* 平台接口自动加载
*
* @version        $Id: autoload.php 2019年03月09日 19:52  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//载入基本类文件
$C['module']['inc']['class'] = array('str','file');
$C['module']['inc']['module'] = array('all');

//引入公共文件
$siteCache = false;
require_once '../inc/common.inc.php';

//url参数分流
$action = Request('action');
//对方法参数进行分割，判断
$aArr = explode('.',$action);
if( count($aArr) == 2 )
{
	//允许访问的接口组
	$apiGroup = array('platform');
	//允许访问的接口
	$apiList = array('weixin');
	if( in_array($aArr[0], $apiGroup) && in_array($aArr[1], $apiList) )
	{
		require_once $aArr[0].'/'.$aArr[1].'/autoload.php';
	}
}
//方法参数错误
else
{
}
?>