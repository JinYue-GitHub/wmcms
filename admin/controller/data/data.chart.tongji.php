<?php
/**
* 数据统计控制器
*
* @version        $Id: data.chaprt.tongji.php 2016年5月9日 10:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//网站运行状态统计
$createTime = intval(file::GetFile(WMCONFIG.'install.lock.txt'));
if( $createTime == '' || $createTime == '0')
{
	$createTime = time();
}
$startTime = date('Y-m-d H:i:s' ,$createTime);
$nowTime = date('Y-m-d H:i:s' ,time());
$runTime = round(( time() - $createTime )/3600/24);

//用户量
$userCount = wmsql::GetCount(array('table'=>'@user_user'));
//小说量
$novelCount = wmsql::GetCount(array('table'=>'@novel_novel'));
//主题量
$bbsCount = wmsql::GetCount(array('table'=>'@bbs_bbs'));
//留言量
$messageCount = wmsql::GetCount(array('table'=>'@message_message'));
//友链量
$linkCount = wmsql::GetCount(array('table'=>'@link_link'));
//文章量
$articleCount = wmsql::GetCount(array('table'=>'@article_article'));
?>