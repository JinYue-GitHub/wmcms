<?php
/**
* 获得小说的分卷列表
*
* @version        $Id: get_volume_list.php 2021年09月02日 20:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));

if( $nid == 0 )
{
	$info = $lang['system']['par']['err'];
}
else
{
	$code = 200;
    $novelMod = NewModel('novel.novel');
	$where['novel_id'] = $nid;
	$where['author_id'] = $author['author_id'];
	$novelData = $novelMod->GetOne( $where );
	if( !$novelData )
	{
		$code = 201;
	}
	else
	{
		$volumeMod = NewModel('novel.volume');
		$data = $volumeMod->GetByNid( $nid );
		if( !$data )
		{
			$code = 201;
		}
	}
	$info = $lang['system']['operate']['success'];
}

ReturnData($info , $ajax , $code , $data);
?>