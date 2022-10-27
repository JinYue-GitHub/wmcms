<?php
/**
* 文章作者来源器文件
*
* @version        $Id: article.author.list.php 2016年4月21日 17:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$articleSer = AdminNewClass('article.article');

//接受post数据
$name = Request('name');
$author = Request('author');

//所有作者或者来源数据
$authorArr = $articleSer->GetAuthor();


if( $orderField == '' )
{
	$where['order'] = 'author_default desc,author_id desc';
}

//获取列表条件
$where['table'] = '@article_author';


//判断是否搜索标题
if( $name != '' )
{
	$where['where']['author_name'] = array('like',$name);
}
else
{
	$name = '请输入关键字';
}
//判断是否按类型搜索
if( $author != '' )
{
	$where['where']['author_type'] = $author;
}

//数据条数
$total = wmsql::GetCount($where);


//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>