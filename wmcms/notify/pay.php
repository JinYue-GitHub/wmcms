<?php
/**
* 支付异步通知操作
*
* @version        $Id: pay.php 2017年7月25日 12:06  weimeng
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

//引入公共支付类
$paySer = NewClass('pay');
$result = $paySer->Notify();
if( $result == false )
{
	//结束程序
	die();
}
?>