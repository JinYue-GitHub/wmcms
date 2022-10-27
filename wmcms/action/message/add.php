<?php
/**
* 新增留言请求处理
*
* @version        $Id: add.php 2016年5月27日 22:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$content = str::IsEmpty( str::DelHtml(Post('content')) , $lang['message']['content_no'] );
str::CheckLen( $content , '10,1000' ,  $lang['message']['content_err'] );

//是否开关评论
if ( $messageConfig['message_open'] <> '1')
{
	ReturnData( $lang['message']['close'] , $ajax );
}
else
{
	//new模型类
	$messageMod = NewModel('message.message');
	
	//是否超过每日限制
	if( $messageMod->GetCount() >= $messageConfig['message_count'] )
	{
		ReturnData( tpl::Rep( array('次数'=>$messageConfig['message_count']) , $lang['message']['count'] ) , $ajax );	
	}

	
	//插入数据
	$messageMod->data['message_content'] = $content;
	$reslut = $messageMod->Insert();

	//插入成功
	if( $reslut )
	{
		ReturnData( $lang['message']['operate']['success'] , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['message']['operate']['fail'] , $ajax );
	}
}
?>