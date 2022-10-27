<?php
/**
* 图集首页
*
* @version        $Id: index.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月4日 10:55 weimeng
*
*/
//引入模块公共文件
require_once 'picture.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['picture']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = picture::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(picture::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

C('page' ,  array(
	'pagetype'=>'picture_type' ,
	'data'=>$data ,
	'tempid'=>'type_titempid' ,
	'dtemp'=>'picture/tindex.html',
	'label'=>'picturelabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$tid,
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>