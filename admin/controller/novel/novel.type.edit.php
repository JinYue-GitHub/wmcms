<?php
/**
* 小说分类控制器文件
*
* @version        $Id: novel.type.edit.php 2016年4月25日 15:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');
$seoSer = AdminNewClass('system.seo');
$conSer = AdminNewClass('system.config');
$typeSer = AdminNewClass('novel.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@novel_type';
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
		$temp['name'] = $tempSer->GetTemp( $data['type_tempid'] , 'temp_name' );
		$temp['cname'] = $tempSer->GetTemp( $data['type_ctempid'] , 'temp_name' );
		$temp['mname'] = $tempSer->GetTemp( $data['type_mtempid'] , 'temp_name' );
		$temp['rname'] = $tempSer->GetTemp( $data['type_rtempid'] , 'temp_name' );
		$temp['tiname'] = $tempSer->GetTemp( $data['type_titempid'] , 'temp_name' );

		//查询使用的静态路径
		$html['tindex'] = $seoSer->GetHtml( $curModule , 'tindex' , $id );
		$html['list'] = $seoSer->GetHtml( $curModule , 'list' , $id );
		$html['content'] = $seoSer->GetHtml( $curModule , 'content' , $id );
		$html['menu'] = $seoSer->GetHtml( $curModule , 'menu' , $id );
		$html['read'] = $seoSer->GetHtml( $curModule , 'read' , $id );
	}
}
else
{
	$data['type_tempid'] = $data['type_ctempid'] = $data['type_mtempid'] = $data['type_rtempid'] = $data['type_titempid'] = 0;
	
	$html['tindex'] = '/html/novel/{tid}.html';
	$html['list'] = '/html/novel/{tid}_{page}.html';
	$html['content'] = '/html/novel/{nid}.html';
	$html['menu'] = '/html/novel/menu/{nid}/{page}.html';
	$html['read'] = '/html/novel/read/{nid}/{cid}.html';
}
	$html['tindex'] = '/html/novel/{tid}.html';
	$html['list'] = '/html/novel/{tid}_{page}.html';
	$html['content'] = '/html/novel/{nid}.html';
	$html['menu'] = '/html/novel/menu/{nid}/{page}.html';
	$html['read'] = '/html/novel/read/{nid}/{cid}.html';
?>