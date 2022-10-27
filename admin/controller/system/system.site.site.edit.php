<?php
/**
* 站内产品线控制器文件
*
* @version        $Id: system.site.site.edit.php 2017年6月12日 15:28  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$manager = AdminNewClass('manager');
$siteSer = AdminNewClass('system.site');

$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$siteMod = NewModel('system.site');
	$data = $siteMod->SiteGetOne($id);
}
else
{
	$data['site_order'] = '99';
}
?>