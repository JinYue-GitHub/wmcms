<?php
/**
* 文章编辑控制器文件
*
* @version        $Id: novel.novel.edit.php 2016年4月16日 23:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$novelSer = AdminNewClass('novel.novel');

//所有推荐属性
$recArr = $novelSer->GetRec();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@novel_rec';
	$where['left']['@novel_novel as n'] = 'rec_nid=novel_id';
	$where['left']['@novel_type as t'] = 't.type_id=n.type_id';
	$where['where']['rec_id'] = $id;

	$data = wmsql::GetOne($where);
}
?>