<?php
/**
* 全系统评论列表请求处理
*
* @version        $Id: list.php 2015年8月16日 16:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2020年06月21日 18:38  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//获取指定的参数
$page = str::Page( Request('page') );
$order = Request('order');
$cid = str::Int( Request( 'cid/i' ) );
$pid = str::Int( Request( 'pid/i' ) );
$sid = str::Int( Request( 'sid/i' ) );
$segId = str::Int( Request( 'segid/i' ) );
$pageCount = str::Int( Request( 'limit/i' ) );
if($pageCount==0)
{
	$pageCount = $replayConfig['list_limit'];
}
$where = array();
$where['replay_subset_id'] = $sid;
$where['replay_segment_id'] = $segId;

//如果排序是热门
if( $order == 'hot' )
{
	$page = 1;
	//显示多少条热门评论
	$pageCount = $replayConfig['hot_count'];
	$order = 'replay_ding desc';
	$where['replay_ding'] = array('>=',$replayConfig['hot_display']);
	$where['replay_pid'] = 0;
}
else
{
	$order = 'replay_time desc';
	//题主模式
	if( $replayConfig['replay_type'] == '2' )
	{
		$where['replay_pid'] = 0;
		if( $pid > 0 )
		{
			$where['replay_pid'] = $pid;
			$pageCount = $replayConfig['replay_replay_page'];
			$order = $replayConfig['replay_replay_order'].' desc';
			if( $replayConfig['replay_replay_order'] == 'replay_id' )
			{
				$order = 'replay_id asc';
			}
		}
	}
}

//数据模型
$replayMod = NewModel('replay.replay');
//设置数据
$replayMod->sid = $sid;
$replayMod->cid = $cid;
$replayMod->order = $order;
$replayMod->module = $module;

//获取列表
$data = $replayMod->GetList($page,$pageCount,$where,$replayConfig['replay_type']);
$data['token'] = FormTokenCreate(true);
$data['data'] = $replayMod->Filter($data['data'],$userConfig['default_head']);
//题主模式获取回复列表
if( $data['data'] && $replayConfig['replay_type'] == '2' && $pid == 0)
{
	$replayMod->order = $replayConfig['replay_replay_order'];
	if( $replayConfig['replay_replay_order'] == 'replay_id' )
	{
		$childWhereAnd = 'a.replay_id > b.replay_id';
	}
	else
	{
		$childWhereAnd = 'a.'.$replayConfig['replay_replay_order'].' < b.'.$replayConfig['replay_replay_order'];
	}

	$childWhere['replay_id'] = array('string','( SELECT COUNT(*) FROM @@replay_replay AS b WHERE a.replay_pid = b.replay_pid AND '.$childWhereAnd.' ) < '.$replayConfig['replay_replay_number']);
	$childWhere['replay_pid'] = array('in-id',str::ArrToStr($data['data'],',',null,null,'replay_id'));
	wmsql::$checkSql = false;
	$childData = $replayMod->GetReplayList(1,$pageCount*$replayConfig['replay_replay_number'],$childWhere);
	$childData = $replayMod->Filter($childData,$userConfig['default_head']);
	$data['data'] = str::ArrAddChild($data['data'],$childData,'replay_id','replay_pid');
}
ReturnData( null , true , 200 , $data );
?>