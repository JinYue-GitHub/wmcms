<?php
/**
* 获得申请详情
*
* @version        $Id: get_apply.php 2022年05月18日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$aid = str::Int(Request('aid'));
$mt = Request('mt');
$worksMod = NewModel('editor.works');
$wordsData = $worksMod->GetApply($aid,$editor['editor_id'],$mt);
//根据id查询
if( $wordsData )
{
	$applyMod = NewModel('system.apply');
	$data['change'] = $applyMod->GetChange($wordsData);
	$data['data'] = str::ShowField($wordsData,'works_group_id,works_cid,works_editor_id,editor_name,apply_id,apply_status,apply_module,apply_type,apply_uid,apply_createtime,apply_updatetime,apply_remark,group_name,group_desc,editor_name,editor_realname,editor_desc,editor_qq,editor_weixin,editor_tel,bind_type,o_editor_name');
	$code = 200;
	$info = $lang['system']['operate']['success'];
}
else
{
	$info = $lang['system']['content']['no'];
}
ReturnData($info , $ajax , $code , $data);
?>