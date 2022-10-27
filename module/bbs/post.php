<?php
/**
* 发表帖子页面
*
* @version        $Id: post.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年2月3日 10:53 weimeng
*
*/
//引入模块公共文件
require_once 'bbs.common.php';


//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );


//接受参数
$tid = str::Int( Get('tid') , $lang['bbs']['type_tid_no'] , 0 );
$bid = str::Int( Get('bid') , null , 0 );


//new一个模型
$bbsMod = NewModel('bbs.bbs');
if( $bid > 0 )
{
	//获得页面的标题等信息
	$where['bbs_id'] = $bid;
	$data = bbs::GetData( 'bbs' , $where );

	//是否是版主
	$isModer = $bbsMod->CheckContentModerator($bid);


	//么有内容就提示
	if ( !$data )
	{
		tpl::ErrInfo( $lang['system']['content']['no'] );
	}
	//不是版主并且不是自己的发帖
	else if( $isModer == false && $data['user_id'] != user::GetUid() )
	{
		tpl::ErrInfo( $lang['bbs']['bbs_noauthor']);
	}
	//状态为待审核中，不是版主并且是作者
	else if( $isModer == false && $data['user_id'] == user::GetUid() && $data['bbs_status'] == '0')
	{
		tpl::ErrInfo( $lang['bbs']['bbs_status_0'] );
	}
	//不是管理员，并且不允许作者自己修改
	else if ( $isModer == false && $data['user_id'] == user::GetUid() && $bbsConfig['author_up'] == 0 )
	{
		ReturnData( $lang['bbs']['bbs_noup']);
	}
}
else
{
	//是否是版主
	$isModer = $bbsMod->CheckModerator($tid);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'bbs_post' ,
	'dtemp'=>'bbs/post.html',
	'label'=>'bbslabel',
	'label_fun'=>'PostLabel',
	'tid'=>$tid,
	'bid'=>$bid,
	'isModer'=>$isModer,
));


//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>