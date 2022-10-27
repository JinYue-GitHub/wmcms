<?php
/**
 * 小说详细信息页面
 *
 * @version        $Id: info.php 2018年7月18日 11:06  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年5月3日 19:18 weimeng
 *
 */
$ClassArr = array('page');
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'novel.common.php';

//当前页面的参数检测
if( $novelConfig['par_info'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['novel']['par']['tid_no'] );
}
$nid = str::IsEmpty( Get('nid') , $lang['novel']['par']['nid_no'] );


//参数验证
$readWhere = $where = novel::GetPar( 'content' , $nid);
//是否检查页面的参数
if( $novelConfig['par_info'] == '1' )
{
	$where = novel::GetPar( 'type' , $tid , $where);
}

//如果是编辑并且存在编辑的数据就不检查小说状态
if( Get('editor')=='1' && editor::CheckEditor() )
{
    C('page.novel_check_status',0);
}
//获得页面的标题等信息
$data = str::GetOne(novel::GetData( 'content' , $where , $lang['system']['content']['no'] ));
$data['novel_name'] = str::DelHtml($data['novel_name']);

//设置seo信息
C('page' ,  array(
	'pagetype'=>'novel_info' ,
	'data'=>$data ,
	'tempid'=>'type_ctempid' ,
	'dtemp'=>'novel/info.html',
	'label'=>'novellabel',
	'label_fun'=>'NovelLabel',
	'tid'=>$data['type_id'],
	'nid'=>$data['novel_id'],
	'novel_pinyin'=>$data['novel_pinyin'],
	'type_pinyin'=>$data['type_pinyin'],
));
tpl::GetSeo();


//点击量自增
$novelMod = NewModel('novel.novel');
$arr = $novelMod->GetIncArr( $data['novel_clicktime'] , 'click');
//更新点击
wmsql::Update( '`@novel_novel`' , $arr , 'novel_id='.$data['novel_id']);


//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址,读取评论
if( class_exists('replay') )
{
	$replayUrl = tpl::Url( 'novel_replay' , array( 'tid'=>$data['type_id'],'nid'=>$data['novel_id'] ) );
	new replay( 'novel' , $data['novel_id'] , $replayUrl , $data['novel_replay'] );
}
$tpl->display();
?>