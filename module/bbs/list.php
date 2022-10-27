<?php
/**
 * 帖子列表页
 *
 * @version        $Id: list.php 2015年11月08日 14:09  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月25日 20:47 weimeng
 *
 */
$ClassArr = array('page');
//引入模块公共文件
require_once 'bbs.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['bbs']['type_tid_no'] );
$page = str::Page();

//参数验证
$where = bbs::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(bbs::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];

C('page' ,  array(
	'pagetype'=>'bbs_list' ,
	'data'=>$data ,
	'tempid'=>'type_tempid' ,
	'dtemp'=>'bbs/list.html',
	'label'=>'bbslabel',
	'label_fun'=>'ListLabel',
	'tid'=>$tid,
	'page'=>$page,
	'listurl'=>tpl::url('bbs_list',array('tid'=>$tid,'tpinyin'=>$data['type_pinyin'],)),
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>