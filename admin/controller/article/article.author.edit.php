<?php
/**
* 文章编辑控制器文件
*
* @version        $Id: article.author.edit.php 2016年4月22日 15:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$articleSer = AdminNewClass('article.article');

//所有作者或者来源数据
$authorArr = $articleSer->GetAuthor();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@article_author';
	$where['where']['author_id'] = $id;

	$data = wmsql::GetOne($where);
}
?>