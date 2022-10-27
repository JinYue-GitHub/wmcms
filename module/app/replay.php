<?php
/**
* 应用评论列表详情
*
* @version        $Id: replay.php 2019年05月12日 16:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'app.common.php';

//当前页面的参数检测
$tid = Get('tid');
$aid = str::Int( Get('aid') , $lang['app']['par']['aid_err'] );

//参数验证
$readWhere = $where = app::GetPar( 'content' , $aid);
//是否检查页面的参数
if( $appConfig['par_app'] == '1' )
{
	$tid = str::IsEmpty( $tid , $lang['app']['par']['tid_no'] );
	$where = app::GetPar( 'type' , $tid , $where);
}


//获得页面的标题等信息
$data = app::GetData( 'content' , $where , $lang['system']['content']['no'] );

C('page' ,  array(
	'pagetype'=>'app_replay' ,
	'data'=>$data[0] ,
	'tempid'=>'type_rtempid' ,
	'dtemp'=>'app/replay.html',
	'label'=>'applabel',
	'label_fun'=>'ReplayLabel',
	'tid'=>$tid,
	'aid'=>$aid,
));
//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址
$replayUrl = tpl::Url( 'app_replay' , array( 'tid'=>C('page.data.type_id'),'cid'=>C('page.data.app_id') ) );
new replay( 'app' , C('page.data.app_id') , $replayUrl );

$tpl->display();
?>