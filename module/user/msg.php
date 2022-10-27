<?php
/**
* 消息内容页
*
* @version        $Id: msg.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月18日 18:18 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

$mid = str::Int( Get('mid') , $lang['user']['mid_err'] );
$where['msg_id'] = $mid;

//获得页面的标题等信息
$data = user::GetData( 'msg' , $where , $lang['user']['msg_no'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_msg' ,
	'dtemp'=>'user/msg.html',
	'label'=>'userlabel',
	'label_fun'=>'MsgLabel',
	'data'=>$data[0],
	'mid'=>$mid,
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();

//修改消息已读状态
if( $data[0]['msg_status'] =='0' )
{
	wmsql::Update('@user_msg', 'msg_status=1', 'msg_id='.$mid);
}

$tpl->display();
?>