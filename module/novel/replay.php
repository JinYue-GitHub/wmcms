<?php
/**
* 小说评论列表
*
* @version        $Id: replay.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月9日 10:23 weimeng
*
*/
$ClassArr = array('page');
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'novel.common.php';

//当前页面的参数检测
$nid = str::IsEmpty( Get('nid') , $lang['novel']['par']['nid_no'] );
//回复ID
$rid = str::Int( Request('rid') );
//祖父ID
$pid = str::Int( Request('pid') );
$page = str::Page( Get('page') );

//参数验证
$where = novel::GetPar( 'content' , $nid);
//是否检查页面的参数
if( $novelConfig['par_replay'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['novel']['par']['tid_no'] );
	$where = novel::GetPar( 'type' , $tid , $where);
}

//获得页面的标题等信息
$data = str::GetOne(novel::GetData( 'content' , $where , $lang['system']['content']['no'] ));
$data['novel_name'] = str::DelHtml($data['novel_name']);

//设置seo信息
C('page' ,  array(
	'pagetype'=>'novel_replay' ,
	'data'=>$data ,
	'tempid'=>'type_rtempid' ,
	'dtemp'=>'novel/replay.html',
	'label'=>'novellabel',
	'label_fun'=>'ReplayLabel',
	'tid'=>$data['type_id'],
	'nid'=>$data['novel_id'],
	'rid'=>$rid,
	'pid'=>$pid,
	'page'=>$page,
	'listurl'=>tpl::url('novel_replay',array('nid'=>$data['novel_id'],'tid'=>$data['type_id'],'npinyin'=>$data['novel_pinyin'],'tpinyin'=>$data['type_pinyin'],)),
));
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址
$replayUrl = tpl::Url( 'novel_replay' , array( 'tid'=>$data['type_id'],'nid'=>$data['novel_id'] ) );
new replay( 'novel' , $data['novel_id'] , $replayUrl );

$tpl->display();
?>