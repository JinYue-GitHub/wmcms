<?php
/**
* 获得小说的分卷的信息
*
* @version        $Id: getnovel.php 2021年09月02日 19:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));
$novelMod = NewModel('novel.novel');
if( $nid == 0 )
{
	$info = $lang['author']['nid_err'];;
}
else
{
	$code = 200;
	$where['novel_id'] = $nid;
	$where['author_id'] = $author['author_id'];
	$novelData = $novelMod->GetOne( $where );
	if( !$novelData )
	{
		$code = 201;
	}
	else
	{
		$data = str::ShowField($novelData,'novel_id,novel_cover,novel_name,novel_sell,novel_sign_id,novel_status,novel_newcname,type_cname,type_id,type_name');
	}
	$info = $lang['system']['operate']['success'];
}
ReturnData($info , $ajax , $code , $data);
?>