<?php
/**
* 权限列表控制器文件
*
* @version        $Id: system.competence.list.php 2016年4月4日 11:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$siteMod = NewModel('system.site');
//接受post数据
$arr = Post();

//获取列表条件
$where['table'] = '@system_competence';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$compArr = wmsql::GetAll($where);


//循环查询出每个权限可以用的权限名字
$wheresql['table'] = '@system_menu';
$wheresql['field'] = 'menu_title';
$wheresql['limit'] = '0,10';

if( $compArr )
{
	foreach ($compArr as $k=>$v)
	{
		$siteStr = $compMenu = '';
		$wheresql['where']['menu_id'] = array('or',$v['comp_content']);
		$compMenuArr = wmsql::GetAll($wheresql);
		//最后一个数组
		$lastCompMenu = end($compMenuArr);
		foreach ($compMenuArr as $key=>$val)
		{
			$compMenu .= $val['menu_title'];
			if ( $lastCompMenu['menu_title'] != $val['menu_title'])
			{
				$compMenu .= ' , ';
			}
		}
		$compArr[$k]['comp_menu'] = $compMenu;
		

		//判断是否是主站的权限
		foreach (explode(',', $v['comp_site']) as $key=>$val)
		{
			if($val=='0')
			{
				$siteStr .= '主站【'.$_SERVER['SERVER_NAME'].'】';
			}
		}
		//其他站点信息
		$siteWhere['where']['site_id'] = array('rin',$v['comp_site']);
		$siteArr = $siteMod->SiteGetAll($siteWhere);
		if( $siteArr )
		{
			$siteStr .= ',';
			//最后一个数组
			$lastCompSite = end($siteArr);
			foreach ($siteArr as $key=>$val)
			{
				$siteStr .= $val['site_title'].'【'.$val['site_domain'].'】';
				if ( $lastCompSite['site_title'] != $val['site_title'])
				{
					$siteStr .= ',';
				}
			}
		}
		$compArr[$k]['comp_site'] = $siteStr;
	}
}
?>