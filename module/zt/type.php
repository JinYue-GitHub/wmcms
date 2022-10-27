<?php
/**
* 专题分类列表
*
* @version        $Id: type.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2018年8月14日 14:50
*
*/
$ClassArr = array('page');
$ModuleArr = array('all');
//引入模块公共文件
require_once 'zt.common.php';

//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['zt']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = zt::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(zt::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

C('page' ,  array(
	'pagetype'=>'zt_type' ,
	'tempid'=>'type_tempid' ,
	'dtemp'=>'zt/type.html',
	'label'=>'ztlabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$tid,
	'page'=>$page,
	'data'=>$data,
	'listurl'=>tpl::url('zt_type',array('tid'=>$tid,'tpinyin'=>$data['type_pinyin'],)),
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>