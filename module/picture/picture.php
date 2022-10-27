<?php
/**
* 图集内容页
*
* @version        $Id: picture.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月4日 13:54 weimeng
*
*/
$ClassArr = array('page');
$ModuleArr = array('replay');
//引入模块公共文件
require_once 'picture.common.php';

//当前页面的参数检测
$tid = Get('tid');
$pid = str::Int( Get('pid') , $lang['picture']['par']['pid_err'] );
$page = str::Page( Get('page') );


//参数验证
$readWhere = $where = picture::GetPar( 'content' , $pid);
//是否检查页面的参数
if( $pictureConfig['par_picture'] == '1' )
{
	$tid = str::IsEmpty( $tid , $lang['picture']['par']['tid_no'] );
	$where = picture::GetPar( 'type' , $tid , $where);
}

//获得页面的标题等信息
$data = str::GetOne(picture::GetData( 'content' , $where , $lang['system']['content']['no'] ));

//设置seo信息
C('page' ,  array(
	'pagetype'=>'picture_picture',
	'data'=>$data,
	'tempid'=>'type_ctempid',
	'dtemp'=>'picture/picture.html',
	'label'=>'picturelabel',
	'label_fun'=>'PictureLabel',
	'tid'=>$tid,
	'pid'=>$pid,
	'page'=>$page,
	'listurl'=>tpl::Url('picture_picture' , array('tid'=>$tid,'tpinyin'=>$data['type_pinyin'],'pid'=>$pid)),
));
tpl::GetSeo();


//阅读量自增
wmsql::Inc( '@picture_picture' , 'picture_read' , $readWhere);

//创建模版并且输出
$tpl=new tpl();

//设置当前页面的地址
if( class_exists('replay') )
{
	$replayUrl = tpl::Url( 'picture_replay' , array( 'tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'],'pid'=>$data['picture_id'] ) );
	new replay( 'picture' , $data['picture_id'] , $replayUrl );
}

$tpl->display();
?>