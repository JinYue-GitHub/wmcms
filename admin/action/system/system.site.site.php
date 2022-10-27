<?php
/**
* 站群配置处理器
*
* @version        $Id: system.site.product.php 2017年6月11日 15:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@site_site';
$siteMod = NewModel('system.site');

//修改、测试配置信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['site_id'] = Request('site_id');
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	//检查域名
	if( !str::CheckUrl($data['site_domain']) )
	{
		Ajax('对不起，请输入完整的域名，必须包含http/https',300);
	}
	
	//小说名字检查
	$wheresql['site_id'] = array('<>',$where['site_id']);
	$wheresql['site_domain'] = $data['site_domain'];
	$wheresql['site_domain_type'] = $data['site_domain_type'];
	if( $siteMod->SiteGetOne($wheresql) )
	{
		Ajax('对不起，该类型的域名站点已经存在！' , 300);
	}
	//如果是新增
	else if ( $type == 'add' )
	{
		//插入记录
		$where['site_id'] = $siteMod->SiteInsert($data);
		//写入操作记录
		SetOpLog( '新增了站内站点'.$data['site_title'] , 'system' , 'insert' , $table , $where , $data );
		Ajax('站内站点新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改站内站点' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$siteMod->SiteUpdate($data,$where);
		Ajax('站内站点修改成功！');
	}
}
//删除站外站点
else if ( $type == 'del' )
{
	$where['site_id'] = GetDelId();
	$siteMod->SiteDel($where);
	//写入操作记录
	SetOpLog( '删除了站内站点' , 'system' , 'delete' , $table , $where);
	Ajax('站内站点删除成功!');
}
//清空站外站点
else if ( $type == 'clear' )
{
	$siteMod->SiteDel();
	//写入操作记录
	SetOpLog( '清空了站内站点' , 'system' , 'delete' , $table , $where);
	Ajax('站内站点清空成功!');
}
//使用禁用站点
else if ( $type == 'status' )
{
	$data['site_status'] = Request('status');
	$where['site_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$siteMod->SiteUpdate($data,$where);
	
	//写入操作记录
	SetOpLog( '站点'.$msg , 'system' , 'update' , $table , $where);
	Ajax('站点'.$msg);
}
//获得站群的信息
else if ( $type == 'getsite' )
{
	$siteType = Request('st',1);
	$siteId = Request('id',1);
	$siteMod = NewModel('system.site');
	$rs['id'] = $siteId;
	$rs['type'] = $siteType;
	if($siteType=='0')
	{
		Session( 'admin_site_id' , '1');
	}
	else if($siteType=='1')
	{
		if( $C['config']['web']['site_open'] == '0' )
		{
			Ajax('对不起，站长关闭了站群模式，您无权限管理该域名!',300);
		}
		else
		{
			$data = $siteMod->SiteGetOne($siteId);
			if(!$data)
			{
				Ajax('对不起，不存在该站点!',300);
			}
			else
			{
				$siteArr = explode(',', Session('admin_site'));
				if( Session('admin_cid') != '0' && !in_array($siteId, $siteArr))
				{
					Ajax('对不起，您没有权限管理该站点!',300);
				}
				else
				{
					Session( 'admin_site_id' , $siteId);
				}
			}
		}
	}
	else
	{
		$data = $siteMod->ProGetOne($siteId);
		if(!$data)
		{
			Ajax('对不起，不存在该站点!',300);
		}
		else
		{
			$domain = $data['product_domain'].'/';
			$path = $data['product_admin'].'/';
			$url = $domain.$path.'index.php?isAjax=0&a=yes&c=login&name='.$data['product_name'].'&psw='.$data['product_psw'];
			$rs['url'] = $url;
		}
	}
	Ajax('请求成功!',200,$rs);
}
?>