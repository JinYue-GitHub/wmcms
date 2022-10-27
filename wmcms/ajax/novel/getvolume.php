<?php
/**
* 获得小说的分卷的信息
*
* @version        $Id: getvolume.php 2017年1月7日 22:40  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$vid = str::Int(Request('vid'));
$code = 500;
$data = array();
$volumeMod = NewModel('novel.volume');

if( $vid == 0 )
{
	$info = $lang['novel']['vid_err'];;
}
else
{
	$code = 200;
	
	$volumeData = $volumeMod->GetOne( $vid );
	if( !$volumeData )
	{
		$code = 201;
	}
	else
	{
		$data['volume_id'] = $volumeData['volume_id'];
		$data['volume_name'] = $volumeData['volume_name'];
		$data['volume_desc'] = $volumeData['volume_desc'];
		$data['volume_order'] = $volumeData['volume_order'];
		$data['volume_time'] = $volumeData['volume_time'];
	}
	$info = $lang['system']['operate']['success'];
}

ReturnData($info , $ajax , $code , $data);
?>