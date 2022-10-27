<?php
/**
* 短信模版编辑控制器文件
*
* @version        $Id: system.set.sms.edit.php 2021年03月17日 15:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$smsMod = NewModel('system.sms');
//接口类型
$where['table'] = '@api_api as a';
$where['order'] = 'api_order';
$where['where']['type_id'] = '9';
$apiArr = wmsql::GetAll($where);

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $smsMod->GetById($id);
}
else
{
}
?>