<?php
/**
* 获得小说数据统计信息
*
* @version        $Id: novel_statistics.php 2022年04月04日 20:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));
$novelMod = NewModel('novel.novel');
$novelWhere['author_id'] = $author['author_id'];
$nids = $novelData = array();
//是否是作者的小说
if( $nid > 0 )
{
    $novelWhere['novel_id'] = $nid;
    $novelData = $novelMod->GetOne( $novelWhere );
}
else
{
    $novelList = $novelMod->GetList( $novelWhere );
    $nids = array_column($novelList,'novel_id');
}

if( $nid>0 && !$novelData )
{
	$code = 201;
	$info = $lang['author']['novel_no'];
}
else
{
	$code = 200;
	$info = $lang['system']['operate']['success'];
    $model = NewModel('author.statistics');
    $data['novel'] = str::HideField($novelData,'author_id,novel_path,type_ctempid,type_key,type_mtempid,type_pid,type_rtempid,type_tempid,type_titempid,type_title,type_topid');
    //点击排名
    $data['click'] = $model->GetNovelRank('click','today,week,month,year,all',$nid,$author['author_id']);
    //收藏排名
    $data['coll'] = $model->GetNovelRank('coll','today,week,month,year,all',$nid,$author['author_id']);
    //推荐排名
    $data['rec'] = $model->GetNovelRank('rec','today,week,month,year,all',$nid,$author['author_id']);
    
    //书架数据
    $data['shelf'] = $model->GetColl('shelf','today,lastday,week,month,year,all,max,avg',$nid,$nids);
    //订阅数据
    $data['sub'] = $model->GetColl('sub','today,lastday,week,month,year,all,max,avg',$nid,$nids);
    //打赏数据
    $data['reward'] = $model->GetReward('today,lastday,week,month,year,all,max,avg',$nid,$nids);
    
    //评论数据
    $data['replay'] = $model->GetReplay('today,lastday,week,month,year,all,max,avg',$nid,$nids);
    //阅读数据
    $data['read'] = $model->GetRead('today,lastday,week,month,year,all,max,avg',$nid,$nids);
    //字数统计数据
    $data['chapter'] = $model->GetChapter('today,lastday,week,month,year,all,max,avg',$nid,$nids);
    
    //月票排名
    $data['ticket_month'] = $model->GetTicket('month','today,week,month,year,all',$nid);
    //推荐票排名
    $data['ticket_rec'] = $model->GetTicket('rec','today,week,month,year,all',$nid);
}
ReturnData($info , $ajax , $code , $data);
?>