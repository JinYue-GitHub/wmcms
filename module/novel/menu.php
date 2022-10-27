<?php
/**
 * 小说目录列表页面
 *
 * @version        $Id: menu.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月8日 9:33 weimeng
 *
 */
$ClassArr = array('page');
//引入模块公共文件
require_once 'novel.common.php';

//当前页面的参数检测
if( $novelConfig['par_menu'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['novel']['par']['tid_no'] );
}
$nid = str::IsEmpty( Get('nid') , $lang['novel']['par']['nid_no'] );
$page = str::Page( Get('page') );


//参数验证
$where = novel::GetPar( 'content' , $nid);
//是否检查页面的参数
if( $novelConfig['par_menu'] == '1' )
{
	$where = novel::GetPar( 'type' , $tid , $where);
}


//获得页面的标题等信息
$data = str::GetOne(novel::GetData( 'content' , $where , $lang['system']['content']['no'] ));
$data['novel_name'] = str::DelHtml($data['novel_name']);

//设置seo信息
C('page' ,  array(
	'pagetype'=>'novel_menu' ,
	'data'=>$data ,
	'tempid'=>'type_mtempid' ,
	'dtemp'=>'novel/menu.html',
	'label'=>'novellabel',
	'label_fun'=>'MenuLabel',
	'tid'=>$data['type_id'],
	'nid'=>$data['novel_id'],
	'page'=>$page,
	'listurl'=>tpl::url('novel_menu',array('tpinyin'=>$data['type_pinyin'],'npinyin'=>$data['novel_pinyin'],'tid'=>$data['type_id'],'nid'=>$data['novel_id'],)),
));
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>