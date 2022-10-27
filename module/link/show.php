<?php
/**
* 友链详情
*
* @version        $Id: show.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月6日 10:36 weimeng
*
*/
//引入模块公共文件
require_once 'link.common.php';

//检查友链展示是否开启
str::EQ( $linkConfig['click_type'] , 0 , $lang['link']['par']['show_close'] );
//当前页面的参数检测
if( $linkConfig['par_link'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['link']['par']['tid_no'] );
}
$lid = str::Int( Get('lid') , $lang['link']['par']['lid_err'] );


//参数验证
$readWhere = $where = link::GetPar( 'content' , $lid);
//是否检查页面的参数
if( $linkConfig['par_link'] == '1' )
{
	$where = link::GetPar( 'type' , $tid , $where);
}


//获得页面的标题等信息
$data = link::GetData( 'content' , $where , $lang['system']['content']['no'] );

C('page' ,  array(
	'pagetype'=>'link_show' ,
	'data'=>$data[0] ,
	'tempid'=>'type_ctempid' ,
	'dtemp'=>'link/show.html',
	'label'=>'linklabel',
	'label_fun'=>'LinkLabel',
	'tid'=>$tid,
	'lid'=>$lid,
));
//设置seo信息
tpl::GetSeo();

//阅读量自增
wmsql::Inc( '@link_link' , 'link_read' , $readWhere);

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>
?>