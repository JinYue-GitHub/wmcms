<?php
/**
* 推荐票、月票等打赏赠送处理器
*
* @version        $Id: ticket.php 2017年3月19日 11:31  weimeng
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
$type = Request('type','rec');
$number = abs(str::Int( Request('number') ));


//查询内容是否存在
$novelData = $tableSer->GetData('novel',$nid);
if( !$novelData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}

//用户金币1数量不足
$ticketMod = NewModel('user.ticket');
$ticket = $ticketMod->GetTicket( $uid , 'novel');
//推荐票不足
if( $type == 'rec' && $ticket['ticket_rec'] < $number )
{
	ReturnData( $lang['novel']['action']['rec_no'] , $ajax );
}
//月票不足
else if( $type == 'month' && $ticket['ticket_month'] < $number )
{
	ReturnData( $lang['novel']['action']['month_no'] , $ajax );
}
else if( $number > 0 )
{
	$authorExp = $rec = $month = 0;
	if( $type == 'rec' )
	{
		$rec = $number;
		//如果收入推荐票的经验值大于0
		if( $authorConfig['income_rec'] > 0 )
		{
			$authorExp = $authorConfig['income_rec'];
		}
	}
	else if( $type == 'month' )
	{
		$month = $number;
		//如果收入月票的经验值大于0
		if( $authorConfig['income_month'] > 0 )
		{
			$authorExp = $authorConfig['income_month'];
		}
	}
	
	//用户推荐票减少
	$ticketMod = NewModel('user.ticket');
	$ticketData['rec'] = $rec;
	$ticketData['month'] = $month;
	$ticketData['remark'] =  $lang['novel']['action']['ticket_consume'];
	$ticketData['module'] =  'novel';
	$ticketData['cid'] =  $nid;
	$ticketMod->Update( $uid , $ticketData , '2');

	//根据票种类增加作者经验值
	if( $authorExp > 0 )
	{
		$expMod = NewModel('author.exp');
		$expMod->Update( 'novel' , $novelData['author_id'] , $authorExp);
	}
	
	//更新小说推荐信息
	$novelMod = NewModel('novel.novel');
	$timeData = $novelMod->GetIncArr( $novelData['novel_rectime'] , 'rec' , $number);
	$novelMod->Update($timeData , $nid);
	
	
	//返回信息
	ReturnData( $lang['novel']['operate'][$type]['success'] , $ajax , 200);
}
?>