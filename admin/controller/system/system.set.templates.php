<?php
/**
* 模版管理控制器文件
*
* @version        $Id: system.set.templates.php 2016年3月29日 13:33  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//获得模版文件夹列表
$path = '../templates/';
//排除的文件夹
$arr = array('system','ajax','site');
$floderArr = file::FloderList( $path , $arr);
$installTempArr = $tempArr = array();

//查询所有模版
if( $floderArr )
{
	foreach ($floderArr as $k=>$v)
	{
		if( file_exists($path.$v['file'].'/copyright.xml') )
		{
			$tempArr[] = GetTempCopy( $v['file'] , $path);
		}
	}
}

//已经安装了的模版
$installTemplates = C('config.templates');
foreach ($installTemplates as $k=>$v)
{
	//如果不存在模版信息删除模版
	if( !file_exists($path.$v['path']) )
	{
		wmsql::Delete('@templates_templates',array('templates_path'=>$v['path']));
	}
	else
	{
		//模版基本信息
		$data = GetTempCopy( $v['path'] );
		
		//是否在使用中
		for( $i = 1 ; $i <= 4 ; $i++ )
		{
			$file = C('config.web.tp'.$i);
			
			$data['tp'.$i] = '0';
			if( $file == $v['path'] )
			{
				$data['tp'.$i] = '1';
			}
		}
	
		$installTempArr[] = $data;
	}
}
?>