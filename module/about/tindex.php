<?php
/**
* 关于分类首页
*
* @version        $Id: tindex.php 2017年4月8日 17:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'about.common.php';

//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['about']['par']['type_err'] );
$page = str::Page( Get('page') );

//参数验证
$where = about::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(about::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];


C('page' ,  array(
	'pagetype'=>'about_tindex' ,
	'data'=>$data ,
	'tempid'=>'type_titempid' ,
	'dtemp'=>'about/tindex.html',
	'label'=>'aboutlabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$data['type_id'],
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>