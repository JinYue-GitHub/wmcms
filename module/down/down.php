<?php
/**
* 下载页面
*
* @version        $Id: down.php 2017年4月28日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月25日 11:51 weimeng
*
*/
//引入模块公共文件
require_once 'down.common.php';

//模块
$module = str::IsEmpty( Get('module') , $lang['down']['par']['module_no']);
//内容id
$cid = str::int( Get('cid') );
//文件id
$fid = str::int( Get('fid') );
//验证参数
if( $module == '' && ($fid == 0 || $cid == 0) )
{
	tpl::ErrInfo($lang['down']['par']['err']);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'down_down' ,
	'dtemp'=>'down/down.html',
	'label'=>'downlabel',
	'label_fun'=>'DownLabel',
	'module'=>$module,
	'cid'=>$cid,
	'fid'=>$fid,
));
//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>