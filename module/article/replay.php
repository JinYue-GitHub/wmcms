<?php
/**
* 文章评论列表
*
* @version        $Id: replay.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年4月24日 13:52 weimeng
*
*/
$ClassArr = array('page');
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'article.common.php';

//当前页面的参数检测
//是否检查页面的参数
if( $articleConfig['par_article'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['article']['par']['tid_no'] );
}
$aid = str::Int( Get('aid') , $lang['article']['par']['aid_err'] );
$page = str::Page( Get('page') );


//参数验证
$where = article::GetPar( 'content' , $aid);
//是否检查页面的参数
if( $articleConfig['par_article'] == '1' )
{
	$where = article::GetPar( 'type' , $tid , $where);
}


//获得页面的标题等信息
$data = str::GetOne(article::GetData( 'content' , $where , $lang['system']['content']['no'] ));

//设置seo信息
C('page' ,  array(
	'pagetype'=>'article_replay' ,
	'data'=>$data,
	'tempid'=>'tempid' ,
	'dtemp'=>'article/replay.html',
	'label'=>'articlelabel',
	'label_fun'=>'ReplayLabel',
	'tid'=>$data['type_id'],
	'aid'=>$data['article_id'],
	'page'=>$page,
	'listurl'=>tpl::url('article_replay',array('tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'],)),
));
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址
$replayUrl = tpl::Url( 'article_replay' , array( 'tid'=>$data['type_id'],'aid'=>$data['article_id'] ) );
new replay( 'article' , $data['article_id'] , $replayUrl );

$tpl->display();
?>