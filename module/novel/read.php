<?php
/**
 * 小说章节阅读页面
 *
 * @version        $Id: read.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月8日 11:08 weimeng
 *
 */
$ClassArr = array('page');
//引入模块公共文件
require_once 'novel.common.php';

//当前页面的参数检测
$cid = str::Int( Get('cid') , $lang['novel']['par']['cid_err'] );


//参数验证
$where['chapter_id'] = $cid;
//是否检查页面的参数
if( $novelConfig['par_read'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['novel']['par']['tid_no'] );
	$nid = str::IsEmpty( Get('nid') , $lang['novel']['par']['nid_no'] );
	
	$where = novel::GetPar( 'type' , $tid , $where);
	$where = novel::GetPar( 'content' , $nid , $where);
}

//获得页面的标题等信息
$data = str::GetOne(novel::GetData( 'read' , $where , $lang['novel']['par']['read_no'] ));
$data['novel_name'] = str::DelHtml($data['novel_name']);
//是否订阅
$data['is_sub'] = 1;


//小说主体的审核状态判断
if( $data['novel_status'] != '1' )
{
	tpl::ErrInfo($lang['system']['content']['no']);
}

//获得处理小说章节
$chapterMod = NewModel('novel.chapter');
$data = $chapterMod->CheckChapterSub($data);
switch ($data)
{
	//章节审核中
	case '201':
		C('code',201);
		tpl::ErrInfo($lang['novel']['par']['chapter_status_0']);
		break;
	//需要登录
	case '202':
		C('code',202);
		tpl::ErrInfo($lang['novel']['par']['chapter_login']);
		break;
	//没有订阅		
	case '203':
		if( $novelConfig['read_sub_prompt'] == '2' )
		{
			$buyUrl = tpl::Rep( array("buy"=>'/wmcms/action/index.php?action=novel.sub&cid='.$cid), $lang['novel']['par']['chapter_buy']);
			C('code',203);
			tpl::ErrInfo($buyUrl);
		}
		break;
	//正常
	default:
		break;
}

//设置seo信息
C('page' ,  array(
	'pagetype'=>'novel_read' ,
	'data'=>$data ,
	'tempid'=>'type_rtempid' ,
	'dtemp'=>'novel/read.html',
	'label'=>'novellabel',
	'label_fun'=>'ReadLabel',
	'tid'=>$data['type_id'],
	'nid'=>$data['novel_id'],
	'cid'=>$cid,
));
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>