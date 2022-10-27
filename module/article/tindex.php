<?php
/**
* 文章分类首页
*
* @version        $Id: tindex.php 2016年6月18日 17:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'article.common.php';

//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['article']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = article::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = str::GetOne(article::GetData( 'type' , $where , $lang['system']['type']['no'] ));
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];
if( $data['type_status'] == '0')
{
	tpl::ErrInfo($lang['system']['type']['no']);
}

C('page' ,  array(
	'pagetype'=>'article_tindex' ,
	'data'=>$data ,
	'tempid'=>'type_titempid' ,
	'dtemp'=>'article/tindex.html',
	'label'=>'articlelabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$data['type_id'],
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>