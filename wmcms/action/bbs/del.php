<?php
/**
* 删除主题请求处理
*
* @version        $Id: del.php 2016年5月29日 17:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年2月22日 13:29  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//提取参数
$bid = str::Int( Request('bid') , $lang['bbs']['bid_err'] );

//检查主题是否存在
$bbsData = $bbsMod->GetOne( $bid );

if( !$bbsData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}
else
{
	//检查是否是版主
	$isModer = $bbsMod->CheckModerator($bbsData['type_id']);

	//版主或者开启了作者自己删除主题
	if( $isModer == true || ($bbsConfig['author_del'] == '1' && $bbsData['user_id'] == $uid) )
	{
		//删除相关的评论
		$data['module'] = $module;
		$data['cid'] = $bid;
		$replayMod = NewModel('replay.replay' , $data);
		$replayMod->Del();
		
		//解绑所有附件
		$uploadMod = NewModel('upload.upload');
		$uploadMod->module = $module;
		$uploadMod->cid = $bid;
		$uploadMod->mid = $bid;
		$uploadMod->UnFileBind();
		
		//删除帖子
		$result = $bbsMod->Del($bid);
		
		if( $result )
		{
			ReturnData( $lang['bbs']['operate']['del']['success'] , $ajax , 200 );
		}
		else
		{
			ReturnData( $lang['bbs']['operate']['del']['fail'] , $ajax );
		}
		
	}
	//不是版主就是提示错误
	else
	{
		ReturnData( $lang['bbs']['comp_err'] , $ajax );
	}
}
?>