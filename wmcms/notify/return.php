<?php
/**
* 同步异步通知操作
*
* @version        $Id: return.php 2020年8月30日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//载入基本类文件
$C['module']['inc']['class'] = array('str','file');
//设置使用模块功能
$C['module']['inc']['module']=array('all');
//引入公共文件
$siteCache = false;
require_once '../inc/common.inc.php';

//引入公共支付类
$paySer = NewClass('pay');
$result = $paySer->ReturnVer();
if( $result['code'] == '0' )
{
	header("location:" . DOMAIN.'/module/user/charge_success.php');
	die();
}
else
{
	tpl::ErrInfo($result['msg']);
}
?>