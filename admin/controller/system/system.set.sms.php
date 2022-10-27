<?php
/**
* 短信模版控制器文件
*
* @version        $Id: system.set.sms.php 2021年03月17日 14:09  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where = array();
$apiName = Request('api_name');
if( $apiName != '' )
{
	$where['sms_api_name'] = $apiName;
}

$smsMod = NewModel('system.sms');
$smsList = $smsMod->GetAll($where);

$apiMod = NewModel('system.api');
$apiList = $apiMod->GetByType(9);
?>