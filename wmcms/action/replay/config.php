<?php
/**
* 系统评论配置获取请求处理
*
* @version        $Id: replay.php 2015年8月16日 16:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年1月28日 11:55  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$data['nickname'] = $replayConfig['nickname'];
$data['head'] = $userConfig['default_head'];

//如果用户大于0
if( user::GetUid() > 0 )
{
	$data['nickname'] = user::GetNickname();
	$data['head'] = user::GetHead();
}

$data['boxname'] = $replayConfig['boxname'];
$data['hot'] = $replayConfig['hot'];
$data['hot_display'] = $replayConfig['hot_display'];
$data['hot_count'] = $replayConfig['hot_count'];
$data['list_limit'] = $replayConfig['list_limit'];
$data['no_data'] = $replayConfig['no_data'];
$data['input'] = $replayConfig['input'];
$data['page_count'] = $replayConfig['page_count'];
$data['no_head'] = $userConfig['default_head'];
$data['replay_replay_number'] = $replayConfig['replay_replay_number'];
$data['replay_replay_page'] = $replayConfig['replay_replay_page'];

ReturnData( null , true , 200 , $data );
?>