<?php
/**
* 应用详情
*
* @version        $Id: index.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月23日 10:18 weimeng
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
	'pagetype'=>'app_app' ,
	'data'=>$data[0] ,
	'tempid'=>'type_ctempid' ,
	'dtemp'=>'app/app.html',
	'label'=>'applabel',
	'label_fun'=>'AppLabel',
	'tid'=>$tid,
	'aid'=>$aid,
));
//设置seo信息
tpl::GetSeo();

//阅读量自增
wmsql::Inc( '@app_app' , 'app_read' , $readWhere);

//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址
$replayUrl = tpl::Url( 'app_replay' , array( 'tid'=>C('page.data.type_id'),'cid'=>C('page.data.app_id') ) );
new replay( 'app' , C('page.data.app_id') , $replayUrl );

$tpl->display();
?>