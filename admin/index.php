<?php
/**
* 后台首页
*
* @version        $Id: index.php 2016年3月21日 13:46  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//设置模块和类文件
$C['module']['inc']['class']=array('file','str');
$isManager = True;

//加载公共文件
require '../wmcms/inc/common.inc.php';
require 'function.php';

//定义空的参数
$cFun = '';

//get/post参数
$get = Get();
$post = Post();

//后台模版类型
$pt = Get('pt');

//控制器
$c = Get('c');
//当前操作的模块
list($curModule) = explode('.', $c);
//写入session
Session('cur_module',$curModule);
//链接请求类型
$type = Get('t');
//处理器
$a = str::IsTrue( Get('a') , 'yes' );
//是否是dialog请求
$d = str::IsTrue( Get('d') , 'yes' );
//后台类型判断
$pt = str::ReturnTrue( IsPhone() , 'web' , 'web');

//后台域名权限验证
CheckDomainAccess();
//检查管理员登录情况
$cPath = CheckLogin($c);

//检查记录管理员的请求
RequestLog();
//如果不是超级管理员权限检测
if ( C('admin_cid') != 0 )
{
	Competence($a , $c , $type);
}

//加载分页参数处理
require 'page.php';
//开启缓冲区
ob_start();
//处理器加载
if ( $a === true )
{
	require 'action/action.php';
}
//界面显示
else
{
	//回调函数名字设置为控制器名字
	$cFun = GetCFun();
	//控制器加载
	require 'controller/controller.php';
	//视图加载
	require 'templates/templates.php';
}
?>