<?php
/**
* 全系统论坛功能请求处理
*
* @version        $Id: bbs.php 2016年2月22日 13:29  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年2月22日 13:29  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

FormTokenCheck();
FormCodeCheck('code_bbs_post');

//提取参数
$bid = str::Int( Post('bid') , null , 0 );
$tid = str::Int( Post('tid') , $lang['bbs']['tid_err'] );
$isreplay = str::Int( Post('isreplay') , null , 0);
$islogin = str::Int( Post('islogin'), null , 0);
$isself = str::Int( Post('isself'), null , 0);
$ispay = str::Int( Post('ispay'), null , 0);
$price = str::Int( Post('price'), null , 0);
$goldtype = str::Int( Post('goldtype'), null , 0);
$title = str::IsEmpty( Post('title') , $lang['bbs']['title_no'] );
$content = str::ClearXSS(str::IsEmpty( Post('content',null,false) , $lang['bbs']['content_no'] ) , 'e');
$tags = Post('tags');

//检查文章长度和标题长度
str::CheckLen( $title , '6,60' , $lang['bbs']['title_lang']);
str::CheckLen( $content , '10,10000' , $lang['bbs']['content_lang']);


//检查分类是否存在
$typeData = $typeMod->GetOne($tid);
if( !$typeData )
{
	ReturnData( $lang['system']['type']['no'] , $ajax );
}

//检查是否是版主
$isModer = $bbsMod->CheckModerator($tid);


//设置帖子参数
$data['bbs_title'] = $title;
$data['type_id'] = $tid;
$data['bbs_isreplay'] = $isreplay;
$data['bbs_islogin'] = $islogin;
$data['bbs_ispay'] = $ispay;
$data['bbs_isself'] = $isself;
$data['bbs_content'] = $content;
$data['bbs_status'] = $bbsConfig['user_post'];
$data['bbs_tags'] = $tags;
//设置上传的参数
$uploadMod->module = 'bbs';
$uploadMod->type = 'bbspost';
$uploadMod->uid = $uid;

//如果帖子id大于0就修改帖子
if ( $bid > 0 )
{
	//检查帖子信息是否存在
	$where['bbs_id'] = $bid;
	$bbsData = $bbsMod->GetOne( $where );

	//帖子不存在
	if( !$bbsData )
	{
		ReturnData( $lang['system']['content']['no'] , $ajax );
	}
	//不是版主并且不是自己的发帖
	else if( $isModer == false && $bbsData['user_id'] != $uid )
	{
		tpl::ErrInfo( $lang['bbs']['comp_err']);
	}
	//不是管理员，并且不允许作者自己修改
	else if ( $isModer == false && ( $bbsData['user_id'] == $uid && $bbsConfig['author_up'] == 0 ))
	{
		ReturnData( $lang['bbs']['bbs_noup'] , $ajax );
	}
	//修改帖子
	else
	{
		//检查帖子是否存在
		$bbsCount = $bbsMod->CheckTitle($title , $bid);
		if( $bbsCount > 0 )
		{
			ReturnData( $lang['bbs']['bbs_exist'] , $ajax );
		}
		

		//上传文件绑定和获取缩略图。
		if( $uploadMod->FileBind('edit',$content,$bbsData['bbs_simg']) )
		{
			$data['bbs_simg'] = $uploadMod->simg;
		}
		
		//修改主题
		$bbsMod->Save( $data , array('bbs_id'=>$bid) );
		
		$info = GetInfo($lang['bbs']['operate']['edit'] , 'bbs_bbs' , array('page'=>1,'bid'=>$bid,'tid'=>$tid,'tpinyin'=>$bbsData['type_pinyin']));
		ReturnData( $info , $ajax , 200 );
	}
}
//否则就插入新的帖子
else
{
	//发帖关闭并且不是版主的时候
	if ( $bbsConfig['post_open'] == '0' && $isModer == false )
	{
		
		ReturnData( $lang['bbs']['post_close'] , $ajax );
	}
	
	
	//检查今日限制
	$todayCount = $bbsMod->CheckTodayPost($uid);
	//今日发帖的数量大于限制的数量，并且限制不为0
	if( $todayCount > $bbsConfig['post_num'] && $bbsConfig['post_num'] != 0 )
	{
		$info = tpl::Rep( array('发帖次数'=>$bbsConfig['post_num']) , $lang['bbs']['post_num'] );
		ReturnData( $info , $ajax );
	}
	
	
	//检查帖子是否存在
	$bbsCount = $bbsMod->CheckTitle($title);
	if( $bbsCount > 0 )
	{
		ReturnData( $lang['bbs']['bbs_exist'] , $ajax );
	}
	

	//插入帖子
	$data['user_id'] = $uid;
	$data['bbs_replay_nickname'] = user::GetNickname();
	$lastId = $bbsMod->Insert($data);
	
	
	if( $lastId > 0 )
	{
		//每天前几次奖励检查,并且限制不为0就写入奖励
		if( $todayCount <= $bbsConfig['post_top'] && $bbsConfig['post_top'] != 0)
		{
			$userMod = NewModel('user.user');
			$userMod->data['user_topic'] = array( '+' , 1 );
			
			$rewardData['gold1'] = $bbsConfig['post_gold1'];
			$rewardData['gold2'] = $bbsConfig['post_gold2'];
			$rewardData['exp'] = $bbsConfig['post_exp'];
			$log['module'] = 'bbs';
			$log['type'] = 'post';
			$log['remark'] = '发帖赠送！';
			$userMod->RewardUpdate( $uid , $rewardData , $log );
		}
		
		//上传文件绑定和设置缩略图
		$uploadMod->cid = $lastId;
		if( $uploadMod->FileBind('add',$content) )
		{
			$bbsMod->Save( array('bbs_simg'=>$uploadMod->simg) , array('bbs_id'=>$lastId) );
		}
		
		//更新版块的发帖数量信息的操作
		$bbsMod->data = $typeData;
		$bbsMod->type = 'post';
		$bbsMod->TypeUp();

		$rsData = array('page'=>1,'bid'=>$lastId,'tid'=>$tid,'tpinyin'=>$typeData['type_pinyin']);
		$info = $lang['bbs']['operate']['post'];
		if( $bbsConfig['user_post'] == '0' )
		{
		    $info = $lang['bbs']['operate']['post_0'];
		}
	    $info = GetInfo($info , 'bbs_bbs' , $rsData);
		//表单token删除
		FormDel();
		ReturnData( $info , $ajax , 200 , $rsData);
	}
	else
	{
		ReturnData( $lang['bbs']['operate']['post']['fail'] , $ajax );
	}
}
?>