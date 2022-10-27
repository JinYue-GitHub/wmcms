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
//引入模块公共文件
require_once 'novel.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['novel']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = novel::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = novel::GetData( 'type' , $where , $lang['system']['type']['no'] );
$data['title'] = $data[0]['type_title'];
$data['key'] = $data[0]['type_key'];
$data['desc'] = $data[0]['type_desc'];


C('page' ,  array(
	'pagetype'=>'novel_tindex' ,
	'data'=>$data[0],
	'tempid'=>'type_titempid' ,
	'dtemp'=>'novel/tindex.html',
	'label'=>'novellabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$data[0]['type_id'],
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>