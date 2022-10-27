<?php
/**
* 配置编辑控制器文件
*
* @version        $Id: system.config.edit.php 2016年4月23日 17:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$configSer = AdminNewClass('system.config');

//所有模块分类
$moduleArr = $configSer->GetModule();
$groupArr = $configSer->GetGroup();
$FromArr = $configSer->GetFromType();



//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@config_config';
	$where['where']['config_id'] = $id;
	
	$data = wmsql::GetOne($where);
}
?>