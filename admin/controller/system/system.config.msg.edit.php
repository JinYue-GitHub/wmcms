<?php
/**
* 消息模版编辑控制器文件
*
* @version        $Id: system.config.msg.edit.php 2020年05月29日 10:52  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$moduleList = GetModuleName();
$msgMod = NewModel('system.msg');

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $msgMod->GetById($id);
}
else
{
}
?>