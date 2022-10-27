<?php
/**
 * 帖子内容页
 *
 * @version        $Id: bbs.php 2015年11月08日 14:09  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月26日 17:05 weimeng
 *
 */
$ClassArr = array('page');
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'bbs.common.php';


//当前页面的参数检测
$bid = str::Int( Get('bid') , $lang['bbs']['type_bid_err'] );
$page = str::Page();

//参数验证
$where = bbs::GetPar( 'content' , $bid);
//是否检查页面的参数
if( $bbsConfig['par_bbs'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['bbs']['type_tid_no'] );
	$where = bbs::GetPar( 'type' , $tid , $where);
}


//获得页面的标题等信息
$data = bbs::GetData( 'bbs' , $where );

//new一个模型
$bbsMod = NewModel('bbs.bbs');
//是否是版主
$isModer = $bbsMod->CheckModerator($data['type_id']);


//么有内容就提示
if ( !$data )
{
	tpl::ErrInfo( $lang['system']['content']['no'] );
}
//状态为待审核中
else if( $data['bbs_status'] == '0' )
{
	//不是管理员、作者不是自己并且作者是自己但是关闭了作者查看
	if ( $isModer == false && $data['user_id'] != user::GetUid() && ( $data['user_id'] == user::GetUid() && $bbsConfig['author_look'] == 0 ))
	{
		tpl::ErrInfo( $lang['bbs']['bbs_status_0'] );
	}
}

//是管理员权限
if ( $isModer == true )
{
	$isEdit = 2;
}
//是作者自己
else if ( $data['user_id'] == user::GetUid() )
{
	$isEdit = 1;
}
//否则不能管理主题
else
{
	$isEdit = 0;
}


//替换内容
$data['bbs_content'] = $bbsMod->RepContent($data['bbs_content'] , $data['user_id'] , $bid);

C('page' ,  array(
	'pagetype'=>'bbs_bbs' ,
	'data'=>$data ,
	'tempid'=>'type_ctempid' ,
	'dtemp'=>'bbs/bbs.html',
	'label'=>'bbslabel',
	'label_fun'=>'BbsLabel',
	'tid'=>$data['type_id'],
	'bid'=>$data['bbs_id'],
	'page'=>$page,
	'edit'=>$isEdit,
));
//设置seo信息
tpl::GetSeo();


//帖子阅读量修改
$bbsMod->data = $data;
$bbsMod->UpBbsInfo( $bid , 'read' );


//创建模版并且输出
$tpl = new tpl();


//设置当前页面的地址
$replayUrl = tpl::Url( 'bbs_bbs' , array( 'tid'=>C('page.data.type_id'),'bid'=>C('page.data.bbs_id') ) );
new replay( 'bbs' , C('page.data.bbs_id') , $replayUrl );


$tpl->display();
?>