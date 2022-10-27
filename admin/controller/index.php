<?php
/**
* 后台首页控制器文件
*
* @version        $Id: index.php 2016年3月24日 13:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//获得快捷菜单
$quickMenuArr = $manager->GetQuickMenu();
//获得后台菜单
$menuArr = $manager->GetMenu();

//把快捷菜单的id转换成一位数组
$newQuickArr = array();
if( $quickMenuArr )
{
	foreach ($quickMenuArr as $k=>$v)
	{
		$newQuickArr[$v['menu_id']] = $v['menu_id'];
	}
}

$siteList = array();
$siteList[] = array('type'=>0,'id'=>0,'domain'=>$_SERVER['SERVER_NAME'],'name'=>C('config.web.webname'));
$siteMod = NewModel('system.site');
//站外站群
if( Session('admin_cid') == '0' )
{
	$proData = $siteMod->ProGetAll(array('where'=>array('product_status'=>1)));
	if( $proData )
	{
		foreach($proData as $k=>$v)
		{
			$data['type'] = 2;
			$data['id'] = $v['product_id'];
			$data['domain'] = $v['product_domain'];
			$data['name'] = $v['product_title'];
			$siteList[] = $data;
		}
	}
}
//开启了站群模式站内站群
if( $C['config']['web']['site_open'] == '1' )
{
	$siteData = $siteMod->SiteGetAll(array('where'=>array('site_status'=>1,'site_type'=>1)));
	if( $siteData )
	{
		foreach($siteData as $k=>$v)
		{
			$data['type'] = 1;
			$data['id'] = $v['site_id'];
			$data['domain'] = $v['site_domain'];
			$data['name'] = $v['site_title'];
			$siteList[] = $data;
		}
	}
	
	//不是超级管理员就循环判断用户的站点权限是否存在
	$siteId = Session( 'admin_site_id');
	if( Session('admin_cid') != '0' )
	{
		$siteArr = explode(',', Session('admin_site'));
		foreach ($siteList as $k=>$v)
		{
			if(!in_array($v['id'], $siteArr))
			{
				unset($siteList[$k]);
			}
		}
	}
}

//默认首页查询
$menuMod = NewModel('system.menu');
$data = $menuMod->DefaultGetOne(Session('admin_id'));
if(!$data)
{
	$defaultIndex='index_main';
}
else
{
	$defaultIndex = $data['default_controller'];
}
?>