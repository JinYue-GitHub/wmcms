<?php
/**
 * 小说排行列表页
 *
 * @version        $Id: toplist.php 2015年11月08日 14:09  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月9日 11:47 weimeng
 *
 */
$ClassArr = array('page');
//引入模块公共文件
require_once 'novel.common.php';


//当前页面的参数检测
$tid = str::Int( Get('tid') , $lang['novel']['par']['tid_err'] );
$type = str::Int( Get('type') , $lang['novel']['par']['type_err'] , 1 );
$page = str::Page( Get('page') );

//参数验证
$where = novel::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = novel::GetData( 'type' , $where , $lang['system']['type']['no'] );

C('page' ,  array(
	'pagetype'=>'novel_toplist' ,
	'data'=>$data[0] ,
	'dtemp'=>'novel/toplist.html',
	'label'=>'novellabel',
	'label_fun'=>'ToplistLabel',
	'tid'=>$tid,
	'type'=>$type,
	'page'=>$page,
	'listurl'=>tpl::url('novel_toplist',array('type'=>$type,'tid'=>$tid)),
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>