<?php
/**
* 邮件服务编辑控制器文件
*
* @version        $Id: system.email.email.edit.php 2016年6月26日 15:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$emailMod = NewModel('system.email');

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $emailMod->EmailGetOne($id);
}
else
{
}
?>