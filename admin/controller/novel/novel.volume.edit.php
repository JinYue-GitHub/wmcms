<?php
/**
* 小说分卷控制器文件
*
* @version        $Id: novel.volume.edit.php 2016年4月28日 11:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Request('id');
$nid = Request('nid');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit' )
{
	$where['table'] = '@novel_volume';
	$where['left']['@novel_novel'] = 'novel_id=volume_nid';
	$where['where']['volume_id'] = $id;
	$data= wmsql::GetOne($where);
	if( $data['volume_nid'] == '0' )
	{
		Ajax('对不起，系统分卷禁止操作',300);
	}
}
//如果书籍的id不为空
if( $nid != '' && $id == '')
{
	$wheresql['table'] = '@novel_novel';
	$wheresql['where']['novel_id'] = $nid;
	$novelData = wmsql::GetOne($wheresql);
	//如果是新增，并且有书籍的id
	if( $type == 'add' )
	{
		$data = $novelData;
	}
}

?>