<?php
/**
* diy内容页
*
* @version        $Id: diy.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年5月10日 12:00 weimeng
*
*/
//引入模块公共文件
require_once 'diy.common.php';

//当前页面的参数检测
$did = str::IsEmpty( Get('did') , $lang['diy']['par']['did_no'] );

//参数验证
$where = diy::GetPar( 'content' , $did);

//获得页面的标题等信息
$data = str::GetOne(diy::GetData( 'content' , $where , $lang['system']['content']['no'] ));
$data['title'] = $data['diy_title'];
$data['key'] = $data['diy_key'];
$data['desc'] = $data['diy_desc'];

//设置默认SEO信息
$seoArr = array(
	'pagetype'=>'index' ,
	'data'=>$data ,
	'tempid'=>'diy_ctempid' ,
	'dtemp'=>'diy/diy.html',
	'label'=>'diylabel',
	'label_fun'=>'DiyLabel',
	'did'=>$data['diy_id'],
);

//设置seo信息
C('page' , $seoArr);
tpl::GetSeo();

//阅读量自增
wmsql::Inc( '@diy_diy' , 'diy_read' , $where);

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>