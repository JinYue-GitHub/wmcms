<?php
/**
* 获得草稿列表的信息
*
* @version        $Id: get_draft_list.php 2021年09月02日 20:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$module = str::IsEmpty( Request('module') , $lang['system']['par']['module_err'] );
$cid = str::Int(Request('cid'));
if( $cid == 0 )
{
	$info = $lang['system']['par']['err'];
}
else
{
	$code = 200;
	$where['draft_module'] = $module;
	$where['draft_cid'] = $cid;
	$where['draft_author_id'] = $author['author_id'];
	$draftMod = NewModel('author.draft');
	$data = $draftMod->GetList($where,'draft_id asc');
	if( !$data )
	{
		$code = 201;
	}
	else
	{
		foreach($data as &$v)
		{
			$v['draft_option'] = unserialize($v['draft_option']);
		}
	}
	$info = $lang['system']['operate']['success'];
}
ReturnData($info , $ajax , $code , $data);
?>