<?php
/**
* 网站关键词处理器
*
* @version        $Id: system.seo.keys.php 2016年4月3日 10:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@seo_keys';
$status = 200;
$msg = '查询成功';

//修改关键词信息
if ( $type == 'edit' || $type == "add"  )
{
	$post = str::Escape($post , 'e');
	
	//如果是修改seo信息
	if ( $type == 'edit' )
	{
		//设置where条件
		$where['keys_id'] = $post['keys_id'];
		unset($post['keys_id']);

		//写入操作记录
		SetOpLog( '修改关键词信息' , 'system' , 'update' , $table  , $where , $post );
		//修改数据
		WMSql::Update($table, $post, $where);
		Ajax('关键词更新成功，请在全部修改完成后点击头部的生存缓存让配置生效!');
	}
	//如果是增加页面
	else
	{
		Ajax('暂未开通新增页面功能',300);
	}
}
//生成静态文件
else if ( $type == 'config' )
{
	$seoSer = AdminNewClass('system.seo');
	$seoSer->UpConfig();

	//写入操作记录
	SetOpLog( '生成了SEO关键字缓存' , 'system' , 'update' );
	Ajax('静态缓存生成成功！');
}
?>