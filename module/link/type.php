<?php
/**
* 友链分类页
*
* @version        $Id: type.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月6日 10:27 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'link.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['link']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = link::GetPar( 'type' , $tid);


//获得页面的标题等信息
$data = str::GetOne(link::GetData( 'type' , $where , $lang['system']['type']['no'] ));

C('page' ,  array(
	'pagetype'=>'link_type' ,
	'data'=>$data,
	'tempid'=>'type_tempid' ,
	'dtemp'=>'link/type.html',
	'label'=>'linklabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$tid,
	'page'=>$page,
	'listurl'=>tpl::url('link_type',array('tid'=>$tid,'tpinyin'=>$data['type_pinyin'],)),
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>