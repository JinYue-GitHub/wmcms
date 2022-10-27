<?php
/**
* 作者申请协议
*
* @version        $Id: agreement.php 2016年12月18日 15:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//是否检查是作者
$isCheck = false;
//引入模块公共文件
require_once 'author.common.php';

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_agreement' ,
	'dtemp'=>'author/agreement.html',
	'label'=>'authorlabel',
	'label_fun'=>'AgreementLabel',
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>