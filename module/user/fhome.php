<?php
/**
 * 好友资料页
 *
 * @version        $Id: fhome.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月15日 21:24 weimeng
 *
 */
//引入模块公共文件
require_once 'user.common.php';

//获得好友的信息
$uid = Get('uid/i');
$where['user_id'] = str::Int( $uid ,  $lang['user']['fuid_err']);
$data = user::GetData( 'user' , $where , $lang['user']['no'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_fhome' ,
	'dtemp'=>'user/fhome.html',
	'label'=>'userlabel',
	'label_fun'=>'FHomeLabel',
	'data'=>$data[0],
	'uid'=>$where['user_id'],
));

//设置seo信息
tpl::GetSeo();


//增加空间访问量
wmsql::Inc('@user_user', 'user_browse', $where);
//访客记录
if( user::GetUid() > 0 && user::GetUid() != $uid )
{
	$arr['vist_uid'] = $where['user_id'];
	$arr['vist_fuid'] = user::GetUid();
	$arr['vist_time'] = time();
	WMSql::Insert('@user_vist', $arr);
}

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>