<?php
/**
* 网站地图列表
*
* @version        $Id: list.php 2017年2月19日 22:23  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//载入类文件
$C['module']['inc']['class']=array('file','str');
//设置使用模块功能
$C['module']['inc']['module']=array('all');

//引入公共文件
require_once '../../../wmcms/inc/common.inc.php';

//设置页面信息
$tid = Request('tid');
$type = Request('type','rss');
$module = Request('module','article');
switch ($type)
{
	case 'rss':
		$dTemp = 'map_rss/'.$module.'.html';
		break;
}
//模块方法不存在就提示
if( !method_exists($module,'GetData') )
{
	tpl::ErrInfo($lang['system']['module']['getdata_no']);
}

//获得页面数据
$where['type_id'] = $tid;
$data = str::GetOne($module::GetData( 'type' , $where , $lang['system']['type']['no'] ));

C('page',array(
	'pagetype'=>'index',
	'dtemp'=>'templates/system/'.$dTemp,
	'tempid'=>'reset',
	'label'=>$module.'label',
	'label_fun'=>'TypeLabel',
	'data'=>$data
));
tpl::GetSeo();

//new一个模版类，然后输出
$tpl=new tpl();
$tpl->display('xml');
?>