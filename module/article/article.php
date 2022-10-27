<?php
/**
* 文章内容文件
*
* @version        $Id: article.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年4月24日 13:52 weimeng
*
*/
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'article.common.php';


//当前页面的参数检测
$aid = str::Int( Get('aid') , $lang['article']['par']['aid_err'] );


//参数验证
$readWhere = $where = article::GetPar( 'content' , $aid);
//是否检查页面的参数
if( $articleConfig['par_article'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['article']['par']['tid_no'] );
	$where = article::GetPar( 'type' , $tid , $where);
}


//获得文章的跳转或者关闭信息
$data = str::GetOne(article::GetData( 'content' , $where , $lang['system']['content']['no'] ));
if( $data['article_url'] != '' )
{
	header("Location: ".$data['article_url']);
}
else if($data['article_display'] == '0' )
{
	tpl::ErrInfo($lang['article']['par']['article_display']);
}
//替换文章的file标签
$articleMod = NewModel('article.article');
$data['article_content'] = $articleMod->RepContent($data['article_content'],$data['article_id']);

//设置seo信息
C('page' ,  array(
	'pagetype'=>'article_article' ,
	'data'=>$data ,
	'tempid'=>'type_ctempid' ,
	'dtemp'=>'article/article.html',
	'label'=>'articlelabel',
	'label_fun'=>'ArticleLabel',
	'tid'=>$data['type_id'],
	'aid'=>$data['article_id'],
));
tpl::GetSeo();


//阅读量自增
wmsql::Inc( '@article_article' , 'article_read' , $readWhere);

//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址
$replayUrl = tpl::Url( 'article_replay' , array( 'tid'=>$data['type_id'],'aid'=>$data['article_id'] ) );
new replay( 'article' , $data['article_id'] , $replayUrl );

$tpl->display();
?>