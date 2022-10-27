<?php
/**
* 论坛分类控制器文件
*
* @version        $Id: bbs.type.edit.php 2016年5月18日 14:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');
$seoSer = AdminNewClass('system.seo');
$typeSer = AdminNewClass('bbs.type');
$conSer = AdminNewClass('system.config');
$bbsConfig = AdminInc('bbs');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@bbs_type';
	$where['where']['type_id'] = $id;

	$data = wmsql::GetOne($where);
	if( $data )
	{
		//上级分类的名字
		if( $data['type_topid'] > 0 )
		{
			$where['where']['type_id'] = $data['type_topid'];
			$topData = wmsql::GetOne($where);
			$top['name'] = $topData['type_name'];
		}
		else
		{
			$top['name'] = '顶级分类';
		}
		
		//查询使用的模版信息
		$temp['name'] = $tempSer->GetTemp( $data['type_tempid'] );
		$temp['cname'] = $tempSer->GetTemp( $data['type_ctempid'] );
		$temp['rname'] = $tempSer->GetTemp( $data['type_rtempid'] );

		//查询使用的静态路径
		$html['list'] = $seoSer->GetHtml( $curModule , 'list' , $id );
		$html['content'] = $seoSer->GetHtml( $curModule , 'content' , $id );
		$html['replay'] = $seoSer->GetHtml( $curModule , 'replay' , $id );
	}
}
//不存在就设置默认值
else
{
	$html['list'] = '/html/bbs/{tid}_{page}.html';
	$html['replay'] = '/html/bbs/{cid}_{page}.html';
	$html['content'] = '/html/bbs/{cid}.html';
	
	$data['type_ico'] = $bbsConfig['default_ico'];
	
	$data['type_tempid'] = $data['type_ctempid'] = $data['type_rtempid'] = 0;

	$data['type_sum_post'] = $data['type_sum_replay'] = $data['type_sum_read'] = 0;
	$data['type_today_post'] = $data['type_today_replay'] = $data['type_today_read'] = 0;
	$data['type_last_post'] = $data['type_last_post'] = $data['type_uptime'] = time();
}
?>