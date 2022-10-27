<?php
/**
* 数据增长统计控制器
*
* @version        $Id: data.chart.add.php 2016年5月9日 10:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$dataSer = AdminNewClass('data.chart');

//统计的时间类型
$timeType = $dataSer->GetTimeType();
$timeArr = $dataSer->GetTime($type);

//当前查询的是什么时间数据
if($type == 'week')
{
	$nowTitle = '本周';
}
else if($type == 'month')
{
	$nowTitle = date('Y-m').'月';
}
else if($type == 'year')
{
	$nowTitle = date('Y').'年';
}

//用户注册增长量
$userArr = str::GetOne($dataSer->GetCount( 'user_user' , 'user_id' , 'user_regtime' ));
//留言增长量
$messageArr = str::GetOne($dataSer->GetCount( 'message_message' , 'message_id' , 'message_time' ));
//主题增长量
$bbsArr = str::GetOne($dataSer->GetCount( 'bbs_bbs' , 'bbs_id' , 'bbs_time' ));
//友链增长量
$linkArr = str::GetOne($dataSer->GetCount( 'link_link' , 'link_id' , 'link_jointime' ));
//小说增长量
$novelArr = str::GetOne($dataSer->GetCount( 'novel_novel' , 'novel_id' , 'novel_createtime' ));
//文章增长量
$articleArr = str::GetOne($dataSer->GetCount( 'article_article' , 'article_id' , 'article_addtime' ));
?>