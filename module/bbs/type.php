<?php
/**
* 论坛版块
*
* @version        $Id: type.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月25日 18:51 weimeng
*
*/
//引入模块公共文件
require_once 'bbs.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['bbs']['type_tid_no'] );

//参数验证
$where = bbs::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(bbs::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

//获得页面的标题等信息
C('page' ,  array(
	'data'=>$data,
	'pagetype'=>'bbs_type' ,
	'dtemp'=>'bbs/type.html',
	'tid'=>$data['type_id'],
	'label'=>'bbslabel',
	'label_fun'=>'TypeLabel',
));
//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>