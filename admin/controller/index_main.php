<?php
/**
* 首页
*
* @version        $Id: index_main.php 2017年2月22日 22:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');
$rs = $cloudSer->GetNewVer();
$newVerData = $rs['data'];


//留言数据
$messageWhere['table'] = '@message_message';
$messageWhere['where']['message_status'] = 0;
$sumMessage = wmsql::GetCount($messageWhere);
$messageWhere['where']['message_time'] = array('>' , strtotime('today') );
$newMessage = wmsql::GetCount($messageWhere);

//用户数据
$userWhere['table'] = '@user_user';
$sumUser = wmsql::GetCount($userWhere);
$userWhere['where']['user_regtime'] = array('>' , strtotime('today') );
$newUser = wmsql::GetCount($userWhere);

//小说数据
$novelWhere['table'] = '@novel_novel';
$sumNovel = wmsql::GetCount($novelWhere);
$novelWhere['where']['novel_createtime'] = array('>' , strtotime('today') );
$newNovel = wmsql::GetCount($novelWhere);
?>