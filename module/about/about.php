<?php
/**
* 关于信息内容页
*
* @version        $Id: about.php 2015年10月14日 20:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年5月13日 18:30 weimeng
*
*/
//引入模块公共文件
require_once 'about.common.php';


//当前页面的参数检测
$aid = str::IsEmpty( Get('aid') , $lang['system']['par']['err'] );

//参数验证
$where = about::GetPar( 'content' , $aid);

//获得页面的标题等信息
$data = str::GetOne(about::GetData( 'content' , $where,$lang['system']['content']['no'] ));
$data['title'] = $data['about_title'];
$data['key'] = $data['about_key'];
$data['desc'] = $data['about_desc'];

$tempid = 'type_ctempid';
if( $data['about_ctempid'] > 0  )
{
	$tempid = 'about_ctempid';
}

$data['title'] = $data['about_title'];
$data['key'] = $data['about_key'];
$data['desc'] = $data['about_desc'];
//设置默认SEO信息
$seoArr = array(
	'pagetype'=>'about_about',
	'data'=>$data,
	'tempid'=>$tempid,
	'dtemp'=>'about/about.html',
	'label'=>'aboutlabel',
	'label_fun'=>'ContentLabel',
	'aid'=>$data['about_id'],
);

//设置seo信息
C('page' , $seoArr);
tpl::GetSeo();



//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>