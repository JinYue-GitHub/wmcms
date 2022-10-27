<?php
/**
* 阅读记录写入
*
* @version        $Id: readset.php 2018年12月15日 14:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//内容id
$cid = str::Int(Request('cid'),$lang['system']['par']['err']);
//内容父id
$nid = str::Int(Request('nid'));
//使用模块
$module = Request('module','novel');
//模块配置
$config = GetModuleConfig($module);
//数据名字
$dataName = '';

//模块没有开启阅读记录
if( $config['read_open'] != '1' )
{
	ReturnData($lang['sys']['readset_close'] , $ajax , 500);
}
//模块是否存在
else if( !GetModuleName($module) )
{
	ReturnData($lang['system']['module']['module_no'] , $ajax , 500);
}
//没有登录
else if( user::GetUid() == 0 )
{
	ReturnData($lang['sys']['no_login'] , $ajax , 500);
}
else
{
	//阅读内容的标题
	if( $module == 'novel' )
	{
		$chapterMod = NewModel('novel.chapter');
		$chapterData = $chapterMod->GetById( $cid );
		if( $chapterData && $chapterData['novel_id'] == $nid )
		{
			$dataName = GetKey($chapterData,'chapter_name');
		}
	}
	else
	{
		$dataName = $tableSer->GetContentName( $module ,$cid );
	}
	
	//阅读内容标题不存在
	if( $dataName == '' )
	{
		ReturnData($lang['sys']['readset_nodata'] , $ajax , 500);
	}
	else
	{
		$readMod = NewModel('user.read');
		$readMod->SetReadLog($module,$nid,$cid,$dataName);
		ReturnData(null , $ajax , 200);
	}
}
?>