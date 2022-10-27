<?php
/**
* API登录返回请求处理
*
* @version        $Id: pay_test.php 2017年7月29日 21:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//载入基本类文件
$C['module']['inc']['class'] = array('str','file');
//引入公共文件
$siteCache = false;
require_once '../inc/common.inc.php';

$test = false;
//是否开启测试。
if( $test == true )
{
	$httpSer = NewClass('http');
	$data ='';
	
	echo $httpSer->GetUrl('http://103.45.4.168:88/wmcms/notify/pay.php',$data);
}
?>