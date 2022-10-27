<?php
/**
* 专题内容页
*
* @version        $Id: zt.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2018年8月14日 20:30 weimeng
*
*/
//引入模块公共文件
$ModuleArr = array('all');
require_once 'zt.common.php';


//当前页面的参数检测
if( $ztConfig['par_zt'] == '1' )
{
	$tid = str::IsEmpty( Get('tid') , $lang['zt']['par']['tid_no'] );
}
$zid = str::IsEmpty( Get('zid') , $lang['zt']['par']['zid_no'] );

//参数验证
$where = zt::GetPar( 'content' , $zid);
//是否检查页面的参数
if( $ztConfig['par_zt'] == '1' )
{
	$where = zt::GetPar( 'type' , $tid , $where);
}

//获得页面的标题等信息
$data = str::GetOne(zt::GetData( 'content' , $where , $lang['system']['content']['no'] ));
$data['zt_name'] = str::DelHtml($data['zt_name']);

//设置seo信息
C('page' ,  array(
	'pagetype'=>'zt_zt' ,
	'tempid'=>'type_ctempid' ,
	'dtemp'=>'zt/zt.html',
	'label'=>'ztlabel',
	'label_fun'=>'ZtLabel',
	'data'=>$data ,
	'tid'=>$data['type_id'],
	'zid'=>$data['zt_id'],
	'type_pinyin'=>$data['type_pinyin'],
));
tpl::GetSeo();


//阅读量自增
wmsql::Inc( '@zt_zt' , 'zt_read' , $where);


//创建模版并且输出
$tpl=new tpl();
//设置当前页面的地址,读取评论
if( class_exists('replay') )
{
	$replayUrl = tpl::Url( 'zt_replay' , array( 'tid'=>$data['type_id'],'zid'=>$data['zt_id'] ) );
	new replay( 'zt' , $data['zt_id'] , $replayUrl , $data['zt_replay'] );
}
$tpl->display();
?>