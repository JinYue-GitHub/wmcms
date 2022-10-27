<?php
/**
* 获得草稿的信息
*
* @version        $Id: get_draft.php 2021年09月05日 12:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$did = str::Int(Request('did'));
$cid = str::Int(Request('cid'));
$module = str::IsEmpty( Request('module') , $lang['system']['par']['module_err'] );
if( $did == 0 )
{
	$info = $lang['author']['draft_did_err'];;
}
else
{
	$code = 200;
	$draftMod = NewModel('author.draft');
	$where['draft_id'] = $did;
	$where['draft_cid'] = $cid;
	$where['draft_module'] = $module;
	$where['draft_author_id'] = $author['author_id'];
	$data = $draftMod->GetOne($where);
	if( !$data )
	{
		$code = 201;
	}
	else
	{
		$data['draft_option'] = unserialize($data['draft_option']);
		$data['draft_content'] = str::EditorFormat($data['draft_content'],true);
	}
	$info = $lang['system']['operate']['success'];
}

ReturnData($info , $ajax , $code , $data);
?>