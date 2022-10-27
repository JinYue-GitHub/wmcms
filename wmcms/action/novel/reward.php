<?php
/**
* 打赏处理器
*
* @version        $Id: reward.php 2017年3月19日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//判断用户是否登录了
$uid = user::GetUid();
str::EQ( $uid , 0 , $lang['user']['no_login']['info'], $ajax );
//是否开启了赠送
str::EQ( $novelConfig['reward_open'], 0 , $lang['novel']['action']['reward_close'] , $ajax );
//接受参数
$nid = str::Int( Request('nid') );
$gold1 = abs(str::Int( Request('gold1') ));
$gold2 = abs(str::Int( Request('gold2') ));

//查询内容是否存在
$novelData = $tableSer->GetData('novel',$nid);
if( !$novelData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}

//用户金币1数量不足
if( $gold1 > user::GetGold1() )
{
	$msg = tpl::Rep(array('金币1名字'=>$userConfig['gold1_name']),$lang['user']['gold1_no']);
	ReturnData( $msg , $ajax );
}
//用户金币2数量不足
else if( $gold2 > user::GetGold2() )
{
	$msg = tpl::Rep(array('金币2名字'=>$userConfig['gold2_name']),$lang['user']['gold2_no']);
	ReturnData( $msg , $ajax );
}
else
{
	//根据作者id查询出用户id，
	$authorMod = NewModel('author.author');
	$authorUid = str::GetKey($authorMod->GetAuthor($novelData['author_id'] , 2) , 'user_id');

	//打赏日志记录
	$rewardData['log_nid'] = $nid;
	$rewardData['log_uid'] = $uid;
	$rewardData['log_gold1'] = $gold1;
	$rewardData['log_gold2'] = $gold2;
	$rewardMod = NewModel('novel.rewardlog');
	$rewardMod->Insert($rewardData);
			
	//用户消费记录
	$userMod = NewModel('user.user');
	$log['module'] = 'novel';
	$log['type'] = 'reward_consume';
	$log['tuid'] = $authorUid;
	$log['cid'] = $nid;
	$log['remark'] = $lang['novel']['action']['reward_consume'];
	$userMod->CapitalChange($uid , $log , $gold1 , $gold2 , 2);

	//小说的资金属性变更
	$data['gold1'] = $gold1;
	$data['gold2'] = $gold2;
	$data['uid'] = $uid;
	$data['nid'] = $nid;
	$data['aid'] = $novelData['author_id'];
	$data['copy'] = $novelData['novel_copyright'];
	$data['sign'] = $novelData['novel_sign_id'];
	$data['log_remark'] = $lang['novel']['action']['reward_income'];
	$data['log_type'] = 'reward_income';
	$data['form'] = 'reward';
	$consumeMod = NewModel('novel.consume');
	$consumeMod->Update($data);
	
	//返回信息
	ReturnData( $lang['novel']['operate']['reward']['success'] , $ajax , 200);
}
?>