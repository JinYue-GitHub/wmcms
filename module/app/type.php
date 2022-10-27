<?php
/**
* 应用分类页
*
* @version        $Id: type.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月21日 15:53 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'app.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['app']['par']['tid_no'] );
/*$lid = str::Int( Get('lid') , $lang['app']['par']['lid_err'] );
$cid = str::Int( Get('cid') , $lang['app']['par']['cid_err'] );
$pid = str::Int( Get('pid') , $lang['app']['par']['pid_err'] );
$ot = str::Int( Get('ot') , $lang['app']['par']['ot_err'] );*/
$page = str::Page( Get('page') );

//参数验证
$where = app::GetPar( 'type' , $tid);


//获得页面的标题等信息
$data = str::GetOne(app::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

C('page' ,  array(
	'pagetype'=>'app_type' ,
	'data'=>$data,
	'tempid'=>'type_tempid' ,
	'dtemp'=>'app/type.html',
	'label'=>'applabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$tid,
	'page'=>$page,
	'listurl'=>tpl::url('app_type',array('tid'=>$tid,'tpinyin'=>$data['type_pinyin'],)),
	/*'lid'=>$lid,
	'cid'=>$cid,
	'pid'=>$pid,
	'ot'=>$ot,
	'listurl'=>tpl::url('app_type',array('cid'=>$cid,'lid'=>$lid,'cid'=>$cid,'pid'=>$pid,'ot'=>$ot,'page'=>'{page}','tid'=>$tid,'tpinyin'=>$data['type_pinyin'],)),*/
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
//分类筛选模块
IncModule('retrieval');
$tpl->display();
?>