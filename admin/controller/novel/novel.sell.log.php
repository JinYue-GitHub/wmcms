<?php
/**
* 小说销售统计控制器
*
* @version        $Id: novel.sell.log.php 2017年8月11日 16:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受参数
$nid = Request('nid',1);
$startTime = Request('start_time',date('Y-m-d'));
$endTime = Request('end_time',date('Y-m-d'));
//接受参数
$startTime = strtotime($startTime.' 00:00:00');
$endTime = strtotime($endTime.' 23:59:59');


$novelMod = NewModel('novel.novel');
$signMod = NewModel('author.sign');
$subMod = NewModel('novel.sublog');
$propsMod = NewModel('props.sell');
$rewardMod = NewModel('novel.rewardlog');
$timeSer = NewClass('time');


//获得小说基本信息
$data = $novelMod->GetOne($nid);
$data['novel_name'] = str::DelHtml($data['novel_name']);

//小说签约等级数据
$data['sign'] = $signMod->GetOne($data['novel_sign_id']);
if( empty($data['sign']) )
{
	$data['sign']['sign_name'] = '暂未签约';
	$data['sign']['sign_divide'] = '10:0';
}


//获得小说区间日期的订阅数据
$data['between']['sub'] = $subMod->GetSumByNid($nid,"{$startTime},{$endTime}");
//获得小说区间日期的道具销售数据
$data['between']['props'] = $propsMod->GetSumByCid('novel',$nid,"{$startTime},{$endTime}");
//获得小说区间日期的打赏数据
$data['between']['reward'] = $rewardMod->GetSumByNid($nid,"{$startTime},{$endTime}");
$data['between']['all'] = $data['between']['sub']+$data['between']['props']+$data['between']['reward'];

//获得小说全部的订阅数据
$data['all']['sub'] = $subMod->GetSumByNid($nid);
//获得小说全部的道具销售数据
$data['all']['props'] = $propsMod->GetSumByCid('novel',$nid);
//获得小说全部的打赏数据
$data['all']['reward'] = $rewardMod->GetSumByNid($nid);
$data['all']['all'] = $data['all']['sub']+$data['all']['props']+$data['all']['reward'];


//获得昨天和今天的时间区间
$yesDayToDay = strtotime('yesterday').','.time();
//获得订阅小说数据
$subData = $subMod->GetByNid($nid,$yesDayToDay);
$data['sub'] = $timeSer->GetListTimeData($subData,array('log_gold2','log_time'));
//获得道具销售数据
$propsData = $propsMod->GetByCid('novel',$nid,$yesDayToDay);
$data['props'] = $timeSer->GetListTimeData($propsData,array('sell_gold2','sell_time'));
//获得打赏销售数据
$rewardData = $rewardMod->GetByNid($nid,$yesDayToDay);
$data['reward'] = $timeSer->GetListTimeData($rewardData,array('log_gold2','log_time'));

//盈利统计
$data['total']['today'] = $data['sub']['today'] + $data['reward']['today'] + $data['props']['today'];
$data['total']['yesterday'] = $data['sub']['yesterday'] + $data['reward']['yesterday'] + $data['props']['yesterday'];


//今日、全部销售排行
$data['sell'] = array(
	'today'=>array(
		'sub'=>$data['sub']['today'],
		'reward'=>$data['reward']['today'],
		'props'=>$data['props']['today'],
	),
	'all'=>array(
		'sub'=>$data['sub']['all'],
		'reward'=>$data['reward']['all'],
		'props'=>$data['props']['all'],
	)
);
?>