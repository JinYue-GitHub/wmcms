<?php
/**
 * 小说列表页
 *
 * @version        $Id: type.php 2015年11月08日 14:09  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月7日 15:47 weimeng
 *
 */
$ClassArr = array('page');
//引入模块公共文件
require_once 'novel.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['novel']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = novel::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(novel::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

C('page' ,  array(
	'pagetype'=>'novel_type' ,
	'data'=>$data,
	'tempid'=>'type_tempid' ,
	'dtemp'=>'novel/type.html',
	'label'=>'novellabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$data['type_id'],
	'page'=>$page,
	'listurl'=>tpl::url('novel_type',array('tid'=>$tid,'tpinyin'=>$data['type_pinyin'],)),
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
//分类筛选模块
IncModule('retrieval');
$tpl->display();
?>