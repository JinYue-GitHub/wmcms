<?php
/**
* 权限编辑控制器文件
*
* @version        $Id: system.competence.edit.php 2016年4月4日 11:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
//权限菜单
$menuArr = $manager->GetMenu(false , 'a');
//获得所有站内站群
$siteList = array();
$siteList[] = array('id'=>0,'domain'=>$_SERVER['SERVER_NAME'],'name'=>'主站');
$siteMod = NewModel('system.site');
$siteData = $siteMod->SiteGetAll(array('where'=>array('site_status'=>1,'site_type'=>1)));
if( $siteData )
{
	foreach($siteData as $k=>$v)
	{
		$data['id'] = $v['site_id'];
		$data['domain'] = $v['site_domain'];
		$data['name'] = $v['site_title'];
		$siteList[] = $data;
	}
}


//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@system_competence';
	$where['where']['comp_id'] = $id;
	$data = wmsql::GetOne($where);
	$siteStr = $menuStr = '';
	if( $data )
	{
		$wheresql['table'] = '@system_menu';
		$wheresql['where']['menu_id'] = array('rin',$data['comp_content']);;
		$menu = wmsql::GetAll($wheresql);
		foreach ($menu as $k=>$v)
		{
			$menuStr .= $v['menu_title'].',';
		}
		
		
		//判断是否是主站的权限
		foreach (explode(',', $data['comp_site']) as $k=>$v)
		{
			if($v=='0')
			{
				$siteStr .= '主站【'.$_SERVER['SERVER_NAME'].'】,';
			}
		}
		//其他站点信息
		$siteWhere['where']['site_id'] = array('rin',$data['comp_site']);
		$siteArr = $siteMod->SiteGetAll($siteWhere);
		if( $siteArr )
		{
			foreach ($siteArr as $k=>$v)
			{
				$siteStr .= $v['site_title'].'【'.$v['site_domain'].'】,';
			}
		}
	}
	$data['comp_content_name'] = $menuStr;
	$data['comp_site_name'] = $siteStr;
}

?>