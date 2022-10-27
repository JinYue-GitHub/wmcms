<?php
/**
* 关于信息列表页
*
* @version        $Id: index.php 2015年10月14日 20:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2017年2月17日 14:52 weimeng
*
*/
//引入模块公共文件
require_once 'about.common.php';

//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['system']['par']['err'] );

//参数验证
$where = about::GetPar( 'type' , $tid);


//获得页面的标题等信息
$data = str::GetOne(about::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

C('page' ,  array(
	'pagetype'=>'about_type' ,
	'data'=>$data ,
	'tempid'=>'tempid' ,
	'dtemp'=>'about/type.html',
	'label'=>'aboutlabel',
	'label_fun'=>'TypeLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>