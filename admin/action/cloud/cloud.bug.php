<?php
/**
* bug请求处理器
*
* @version        $Id: cloud.bug.php 2017年2月22日 22:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');

//获得留言分类操作
if ( $type == 'gettype' )
{
	$rs = $cloudSer->GetMessageType();
	Ajax('请求成功！',200,$rs['data']);
}
//获得反馈列表
else if ( $type == 'getlist' )
{
	$page = str::Page(Request('page'));
	$tid = str::Int(Request('tid'));
	$pageCount = str::Int(Request('pagecount' , 20));
	$isUser = str::Int(Request('isuser'));
	$rs = $cloudSer->GetMessageList($tid , $page , $pageCount,$isUser);
	
	Ajax('请求成功！',200,$rs);
}
//提交新的反馈
else if ( $type == 'add' )
{
	$tid = str::Int(Request('tid'));
	$open = str::Int(Request('open'));
	$domainShow = str::Int(Request('domainshow'));
	$content = Request('content');
	$rs = $cloudSer->MessageAdd($tid , $open , $domainShow , $content);
	
	//写入操作记录
	SetOpLog( '提交了新的反馈到云中心！' , 'system' , 'update' );
	Ajax('反馈成功！',200,$rs);
}
?>