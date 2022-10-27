<?php
/**
* 文章列表页
*
* @version        $Id: type.php 2015年11月08日 14:09  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月29日 13:51 weimeng
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
if( isset($data['type_status']) && $data['type_status'] == '0')
{
	tpl::ErrInfo($lang['system']['type']['no']);
}
$data['title'] = $data['type_title'];
$data['key'] = $data['type_key'];
$data['desc'] = $data['type_desc'];
C('page' ,  array(
	'pagetype'=>'article_type' ,
	'data'=>$data ,
	'tempid'=>'type_tempid' ,
	'dtemp'=>'article/type.html',
	'label'=>'articlelabel',
	'label_fun'=>'TypeLabel',
	'tid'=>$data['type_id'],
	'page'=>$page,
	'listurl'=>tpl::url('article_type',array('tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'],)),
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>