<?php
/**
* 配置选项编辑制器文件
*
* @version        $Id: system.seo.keys.edit.php 2016年4月23日 17:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$configSer = AdminNewClass('system.config');

//所有模块分类
$moduleArr = $configSer->GetModule();
//所有配置分组
$groupArr = $configSer->GetGroup();



//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


$data = '';
//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@config_option as o';
	$where['left']['@config_config as c'] = 'o.config_id=c.config_id';
	$where['left']['@config_group as g'] = 'c.group_id=g.group_id';
	$where['where']['o.config_id'] = $id;
	$where['order']= 'option_order';
	
	$data = wmsql::GetAll($where);
}
?>