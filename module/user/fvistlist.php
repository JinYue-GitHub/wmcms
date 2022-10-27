<?php
/**
 * 好友访客列表
 *
 * @version        $Id: fvistlist.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月19日 20:49 weimeng
 *
 */
$ClassArr = array('page');
//引入模块公共文件
require_once 'user.common.php';


$where['user_id'] = str::Int( Get('uid') ,  $lang['user']['fuid_err']);
$page = str::Page();

//获得页面的标题等信息
$data = user::GetData( 'user' , $where , $lang['user']['no'] );

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_fvistlist' ,
	'dtemp'=>'user/fvistlist.html',
	'label'=>'userlabel',
	'label_fun'=>'FVistListLabel',
	'page'=>$page,
	'uid'=>$where['user_id'],
	'listurl'=>tpl::url( 'user_fvistlist' , array('uid'=>$where['user_id']) ),
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>