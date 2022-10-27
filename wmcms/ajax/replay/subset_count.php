<?php
/**
* 获得子内容的评论数量
*
* @version        $Id: subset_count.php 2021年08月08日 16:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2020年06月21日 18:38  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//获取指定的参数
$module = str::IsEmpty( Request('module') , $lang['replay']['module_empty'] );
$cid = str::Int( Request( 'cid/i' ) );
$sid = str::Int( Request( 'sid/i' ) );

//数据模型
$replayMod = NewModel('replay.replay');
$data = $replayMod->GetSubsetCount($module,$cid,$sid);
ReturnData( null , true , 200 , $data );
?>