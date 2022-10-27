<?php
/**
* 删除消息操作处理
*
* @version        $Id: delmsg.php 2016年5月28日 21:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
$mid = str::Int( Request('mid') , $lang['user']['mid_err'] );


//new一个消息模型
$msgMod = NewModel('user.msg');
//设置消息的条件
$msgMod->msgId = $mid;
$msgMod->userId = user::GetUid();

//查询这条数据是否存在
$data = $msgMod->GetOne();


//如果消息存在，并且收信人为自己，就删除
if ( $data && $data['msg_tuid'] == user::GetUid() )
{
	//删除一条数据
	$result = $msgMod->DelOne();
	
	if( $result )
	{
		$info['time'] = 1;
		$info['html'] = $lang['system']['operate']['autojump'];
		$info['gourl'] = tpl::url( 'user_msglist');
		$info['info'] = $lang['user']['operate']['delmsg']['success'];
		ReturnData( $info , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['user']['operate']['delmsg']['fail'] , $ajax);
	}
}
//消息存在并且自己不是收信人
else if ( $data )
{
	ReturnData( $lang['user']['no_auth'] , $ajax);
}
//没有数据
else
{
	ReturnData( $lang['user']['msg_no'] , $ajax);
}
?>