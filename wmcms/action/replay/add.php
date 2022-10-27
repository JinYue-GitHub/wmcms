<?php
/**
* 全系统评论/回帖功能请求处理
*
* @version        $Id: replay.php 2015年8月16日 16:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年1月28日 11:55  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//评论关闭
if( $replayConfig['replay_open'] == '0' )
{
	ReturnData( $lang['replay']['close'] , $ajax );
}
//如果是bbs就必须要登录
else if( $module == 'bbs' && user::GetUid() == 0)
{
	ReturnData( $lang['replay']['bbs_login'] , $ajax );
}
//没有登录
else if( $replayConfig['replay_login'] == '1' && user::GetUid() == 0)
{
	ReturnData( $lang['replay']['login'] , $ajax );
}
else
{
	//接受参数
	$cid = str::Int(Post( 'cid' ) , $lang['replay']['par']);
	$rid = str::Int(Post( 'rid' ));
	$sid = str::Int( Request( 'sid/i' ) );
	$segId = str::Int( Request( 'segid/i' ) );
	//评论内容
	$content = str::DelHtml(str::IsEmpty( Post('content') , $lang['replay']['par'] ) );
	//检查长度
	str::CheckLen( $content , '4,10000', $lang['replay']['content'] );
	//默认赋值
	$uid = user::GetUid();
	//默认头像
	if( $uid > 0 )
	{
		$nickname = user::GetNickname();
		$head = user::GetHead();
	}
	else
	{
		$nickname = $replayConfig['nickname'];
		$head = $userConfig['default_head'];
	}
	
	//检查是否被禁言中
	$punishMod = NewModel('user.punish');
	if( $punishMod->GetOne($uid,'talk') )
	{
		ReturnData( $lang['replay']['punish'] , $ajax );
	}
	
	//new一个评论模型
	$modData['module'] = $module;
	$modData['cid'] = $cid;
	$replayMod = NewModel('replay.replay' , $modData);
	//检测回复人信息
	if( $rid > 0 )
	{
		$rData = $replayMod->GetById($rid);
		if( $rData )
		{
			$replayData['replay_rid'] = $rid;
			$replayData['replay_ruid'] = $rData['replay_uid'];
			$replayData['replay_rnickname'] = $rData['replay_nickname'];
			$replayData['replay_pid'] = $rid;
			if( $rData['replay_pid'] > 0 )
			{
				$replayData['replay_pid'] = $rData['replay_pid'];
			}
			if( $replayData['replay_ruid'] > 0 )
			{
				$replayData['replay_rnickname'] = $rData['user_nickname'];
			}
		}
		else
		{
			$rid = 0;
		}
	}
	
	//new上传图片模型
	$uploadMod = NewModel('upload.upload');
	$uploadMod->module = $module;
	$uploadMod->type = $type;
	$uploadMod->uid = $uid;
	$uploadMod->mid = $cid;
	
	//获取上一次评论的数据
	$topFloor = $replayMod->GetTopFloor();
	//首先检查指定模块的前置配置信息
	switch ($module)
	{
		case 'bbs':
			//如果是回帖可以使用html代码
			$content = str::ClearXSS(str::IsEmpty( Post('content',null,false) , $lang['replay']['par'] ));
			$bbsMod = NewModel('bbs.bbs');
			$isModer =  $bbsMod->CheckContentModerator($cid);
			//论坛回帖默认是二楼。
			if( $topFloor == '1' )
			{
				$topFloor = '2';
			}
			//不是是否是版主并且已经关闭了回帖
			if ( $isModer == false && $bbsMod->data['type_replay_open'] == '0')
			{
				ReturnData($lang['replay']['bbs_close'] , $ajax);
			}
			$codeType = 'code_bbs_replay';
			break;
			
		default:
			$codeType = 'code_replay';
			break;
	}

	//获取上一次评论的数据
	$topData = $replayMod->CheckPre($module);
	//上次评论的时间小于间隔时间提示
	$topTime =  time() - 0;
	if( isset($topData['replay_time']) )
	{
    	$topTime =  time() - $topData['replay_time'];
	}
	if ( $topTime < $replayConfig['top_time'] )
	{
		$topTimeInfo = str_replace( '{间隔}' , $replayConfig['top_time'] , $lang['replay']['top_time'] );
		str::LT( $topData['replay_time'] - time(), $replayConfig['top_time'] , $topTimeInfo , $ajax );
	}
	
	//检查需要评论的内容是否存在。
	str::LT( $replayMod->GetContentCount() , 1 , $lang['replay']['replay_no'] );


	//获得今日的评论条数。
	$todayCount = $replayMod->GetTodayCount();

	/**
	 * 是否开启每日评论次数限制
	 * 如果今日评论的条数大于等于限制的条数
	 */
	if ( $replayConfig['everyday_count'] > 0 && $todayCount >= $replayConfig['everyday_count'] )
	{
		ReturnData( tpl::Rep( array( '条数'=>$replayConfig['everyday_count'] ) , $lang['replay']['everyday_limit'] ) , $ajax );
	}

	FormTokenCheck();
	FormCodeCheck($codeType);
	
	/**
	 * 如果是登录用户评论，就检查是否有评论奖励为0就不奖励
	 * 对用户进行评论奖励,并且金币和经验奖励都不为0
	 */
	if( user::GetUid() > 0 && $todayCount < $replayConfig['reward_count'] 
		&& ($replayConfig['replay_gold1'] > 0 || $replayConfig['replay_gold2'] > 0 || $replayConfig['replay_exp'] > 0) )
	{
		//新的用户模型
		$userMod = NewModel('user.user');
		//更新奖励操作
		if( $module == 'bbs' )
		{
			$userMod->data['user_retopic'] = array( '+' , 1 );
			$rewardData['gold1'] = $bbsConfig['replay_gold1'];
			$rewardData['gold2'] = $bbsConfig['replay_gold2'];
			$rewardData['exp'] = $bbsConfig['replay_exp'];
		}
		else
		{
			$userMod->data['user_replay'] = array( '+' , 1 );
			$rewardData['gold1'] = $replayConfig['replay_gold1'];
			$rewardData['gold2'] = $replayConfig['replay_gold2'];
			$rewardData['exp'] = $replayConfig['replay_exp'];
		}
		
		$log['module'] = 'replay';
		$log['type'] = 'add';
		$log['cid'] = $cid;
		$log['remark'] = '评论赠送！';
		$userMod->RewardUpdate( $uid , $rewardData , $log );
	}
	
	//设置评论数据
	$replayData['replay_floor'] = $topFloor;
	$replayData['replay_status'] = $replayConfig['status'];
	$replayData['replay_uid'] = $uid;
	$replayData['replay_nickname'] = $nickname;
	$replayData['replay_content'] = $content;
	$replayData['replay_subset_id'] = $sid;
	$replayData['replay_segment_id'] = $segId;
	//插入评论数据
	$result = $replayMod->Insert( $replayData );

	//返回提示
	if( !$result )
	{
		ReturnData( $tishi['fail']  , $ajax );
	}
	else
	{
		//根据不同模块做出相应的提示操作
		if( $module == 'bbs' )
		{
			//绑定设置类型
			$uploadMod->type = 'bbsreplay';
			
			$tishi = $lang['operate']['bbs'];
			//更新回帖后的操作
			$bbsMod->UpBbsInfo($cid);
		}
		else
		{
			//绑定设置类型
			$uploadMod->type = 'replay';
			
			$tishi = $lang['operate']['replay'];
		}
		//绑定图片
		$uploadMod->cid = $result;
		$uploadMod->FileBind('add',Post('content'));
		
		//评论通知
		$msgMod = NewModel('user.msg');
		//通知回复的用户
		if( $rid > 0 && $replayData['replay_ruid']>0 )
		{
			//更新当前评论的总回复数量
			$pCount = $replayMod->GetPidCount($replayData['replay_pid']);
			$replayMod->Update(array('replay_count'=>$pCount),array('replay_id'=>$replayData['replay_pid']));
			
			$msgMod->SendMsg($module,$cid,$uid,'@'.$replayData['replay_rnickname'].' '.$content);
		}
		else
		{
			$msgMod->SendMsg($module,$cid,$uid,$content);
		}
		
		//更新模块评论次数加一
		$replayMod->ContentInc();
		
		//返回提示
		if( !$result )
		{
			ReturnData( $tishi['fail']  , $ajax );
		}
		else
		{
			//表单token删除
			FormDel();
			
			//替换评论内容的表情
			$replayData['user_head'] = $head;
			$replayData['replay_time'] = time();
			$replayData['token'] = FormTokenCreate(true);
			$newData = $replayMod->RepReplayFace( $replayData , tpl::Url('user_fhome') , $userConfig['default_head'] );
			ReturnData( $tishi['success'] , $ajax , 200 ,$newData );
		}
	}
}
?>