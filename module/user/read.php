<?php
/**
* 用户阅读记录列表
*
* @version        $Id: coll.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月21日 14:31 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'user.common.php';


//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );

//当前页面的参数检测
$module = Get('module');
$page = str::Page();

//检查模块收藏是否存在
$moduleArr = array('novel');
if ( !in_array( $module , $moduleArr) )
{
	tpl::ErrInfo($lang['user']['read_module']);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_read' ,
	'dtemp'=>'user/read_'.$module.'.html',
	'label'=>'userlabel',
	'label_fun'=>'ReadLabel',
	'module'=>$module,
	'page'=>$page,
	'listurl'=>tpl::Url('user_read',array('module'=>$module)),
));
//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>