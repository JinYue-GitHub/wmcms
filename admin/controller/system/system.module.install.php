<?php
/**
* 模块绑定功能控制器文件
*
* @version        $Id: system.module.config.php 2016年9月14日 10:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$installModule = array();
$moduleMod = NewModel('system.module');
$moduleList = file::FloderList('../module/');

//读取当前使用的基本模块
$moduleArr = tpl::Tag("C['module']['inc']['module']=array('[a]');", file_get_contents('../index.php'));
$moduleArr = explode("','",$moduleArr[1][0]);
if( count($moduleArr) == 1 && $moduleArr[0] == 'all' )
{
	foreach( $moduleList as $k=>$v)
	{
		$installModule[] = $v['file'];
	}
}
else
{
	$installModule = $moduleArr;
}
?>