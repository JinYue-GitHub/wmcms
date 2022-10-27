<?php
/**
* 网站伪静态设置处理器
*
* @version        $Id: system.seo.rewrite.php 2016年4月6日 10:32  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@seo_urls';
$status = 200;
$msg = '查询成功';

//修改关键词信息
if ( $type == 'edit' || $type == "add"  )
{
	$post = str::Escape($post , 'e');
	
	//如果是修改URL信息
	if ( $type == 'edit' )
	{
		//设置where条件
		$where['urls_id'] = $post['urls_id'];
		unset($post['urls_id']);

		//写入操作记录
		SetOpLog( '修改SEO的URL信息' , 'system' , 'update' , $table, $where , $post );
		WMSql::Update($table, $post, $where);
		Ajax('伪静态更新成功，请在全部修改完成后点击头部的生存缓存让配置生效!');
	}
	//如果是增加页面
	else
	{
		Ajax('暂未开通新增URL功能',300);
	}
}
//生成静态文件
else if ( $type == 'config' )
{
	$seoSer = AdminNewClass('system.seo');
	$seoSer->UpConfig();

	//写入操作记录
	SetOpLog( '生成了SEO的URL缓存' , 'system' , 'update' );
	Ajax('静态缓存生成成功！');
}
?>