<?php
/**
* 重置密码页面
*
* @version        $Id: repsw.php 2016年5月4日 15:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'user.common.php';

//获取参数
$key = str::IsEmpty( Get('key')  , $lang['user']['no_key'] );
//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_repsw' ,
	'dtemp'=>'user/repsw.html',
	'label'=>'userlabel',
	'label_fun'=>'RepswLabel',
	'key'=>$key,
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>