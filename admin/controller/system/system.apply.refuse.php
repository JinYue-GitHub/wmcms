<?php
/**
* 拒绝审核控制器文件
*
* @version        $Id: system.apply.refuse.php 2017年1月14日 13:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$module = Request('module');
$mt = Request('type');
$uid = Request('uid');
$cid = Request('cid');
$id = Request('id');
$applyMod = NewModel('system.apply',$module);
$applyData = $applyMod->GetById($id);
if( $module == '' || $type == '' || $mt == '' || !str::Number($uid) || !str::Number($cid) )
{
	Ajax('对不起，参数错误',300);
}
else if( !$applyData || $applyData['apply_cid']!=$cid )
{
	Ajax('对不起，参数错误',300);
}
else
{
    $pid = $applyData['apply_pid'];
	$remark = str::ToHtml($applyMod->GetHandleRemark($mt));
}
?>