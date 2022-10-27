<?php
/**
* 全系统评分功能请求处理
*
* @version        $Id: app.php 2016年5月27日 23:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//设置分数范围对应的字段。
$scoreArr = array(
	'1'=>'score_one',
	'2'=>'score_two',
	'3'=>'score_three',
	'4'=>'score_four',
	'5'=>'score_five',
);

//分数范围是否正确
if ( GetKey($scoreArr,$score) == '' )
{
	tpl::ErrInfo( $lang['score']['score_no'] );
}
//限制条数
if( GetKey($config,'score_count') == '' )
{
	$config['score_count'] = '1';
}
//检查评分功能是否开启
if ( GetKey($config,'score_open') < 1 )
{
	ReturnData( $lang['score']['score_close'] , $ajax );
}
//检查是否需要登录
if ( GetKey($config,'score_login') == 1 && User::GetUid()=='0' )
{
	ReturnData( $lang['system']['user']['no_login'] , $ajax );
}


//new模型
$data['module'] = $module;
$data['cid'] = $cid;
$data['type'] = 'score';
$operateMod = NewModel('operate.operate' , $data);


//查询需要评论的内容是否存在
$contentCount = $operateMod->GetContentCount();
//今日评分的次数
$todayCount = $operateMod->CheckOperateCount();


//是否存在评分的内容
if ( $contentCount < 1 )
{
	tpl::ErrInfo( $lang['score']['content_no'] );
}
//每天限制的评分次数
else if ( $todayCount >= $config['score_count'] && $config['score_count'] > 0)
{
	ReturnData( tpl::Rep( array( '次数'=>$config['score_count'] ) , $lang['score']['score_count'] ) , $ajax );
}
else
{
	//更新评分数据，并且接受平均分数
	$operateMod->score = $score;
	$operateMod->scoreArr = $scoreArr;
	$scoreNumber = $operateMod->UpdateScore();
	//插入用户评分操作记录
	$result = $operateMod->Insert();
	//更新内容评分字段的分数
	$operateMod->ContentSvg();
	
	$resultData['score'] = $scoreNumber;

	//返回成功提示
	if( $result )
	{
		ReturnData( $lang['score']['operate']['score']['success'] , $ajax , 200 , $resultData);
	}
	else
	{
		ReturnData( $lang['score']['operate']['score']['fail'] , $ajax);
	}
}
?>