<?php
/**
* 处理申请
*
* @version        $Id: apply.php 2022年05月19日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$aid = str::Int(Request('aid'));
$mt = Request('mt');
$status = str::Int(Request('status'));
$remark = str::DelHtml(Request('remark'));
//如果是拒绝操作理由就不能为空
if( $status=='2' )
{
    str::IsEmpty($remark , $lang['editor']['refuse_remark_empty']);
    str::CheckLen( $remark , $len = '10,200' , $lang['editor']['refuse_remark_len']);
}

//根据id查询
$worksMod = NewModel('editor.works');
$wordsData = $worksMod->GetApply($aid,$editor['editor_id'],$mt);
if( $wordsData && $wordsData['apply_status']=='0' )
{
    $applyMod = NewModel('system.apply');
    $result = $applyMod->ApplyHandle($aid,$status,$remark,$editor['editor_id']);
	if( $result )
	{
    	$code = 200;
    	$info = $lang['system']['operate']['success'];
	}
	else
	{
    	$info = $lang['system']['operate']['fail'];
	}
}
else if( $wordsData )
{
    $info = $lang['editor']['already_handle'];
}
else
{
	$info = $lang['system']['content']['no'];
}
ReturnData($info , $ajax , $code , $data);
?>