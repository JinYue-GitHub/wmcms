<?php
/**
* 忘记密码页面
*
* @version        $Id: getpsw.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月15日 16:25 weimeng
*
*/
//引入模块公共文件
require_once 'user.common.php';


//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_getpsw' ,
	'dtemp'=>'user/getpsw.html',
	'label'=>'userlabel',
	'label_fun'=>'GetPswLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>