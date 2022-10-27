<?php
/**
* 待审文章列表控制器文件
*
* @version        $Id: author.novel.chapter.list.php 2017年1月19日 21:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$st = Request('type');
$name = Request('name');


if( $orderField == '' )
{
	$where['order'] = 'apply_id desc';
}

//判断是否搜索标题
if( $name != '' )
{
	switch ($st)
	{
		case '1':
			$filed = 'article_name';
			break;

		case '2':
			$filed = 'article_author';
			break;
			
		default:
			$filed = 'article_id';
			break;
	}
	$where['where'][$filed] = array('like',$name);
}
else
{
	$name = '请输入标题关键字';
}


//获取列表条件
$where['table'] = '@system_apply as a';
$where['left']['@article_article as n'] = 'article_id=apply_cid';
$where['left']['@article_type as t'] = 't.type_id=n.type_id';
$where['where']['apply_module'] = 'author';
$where['where']['apply_type'] = 'article_editarticle';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>