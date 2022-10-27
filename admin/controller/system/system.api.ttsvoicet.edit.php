<?php
/**
* 内容标签控制器
*
* @version        $Id: system.api.ttsvoicet.edit.php 2022年04月21日 16:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$voicetMod = NewModel('system.apittsvoicet');

$id = Get('id');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit')
{
	$data = $voicetMod->GetById($id);
}

//接口数据
$apiMod = NewModel('system.api');
$typeData = $apiMod->GetByType(11);
?>