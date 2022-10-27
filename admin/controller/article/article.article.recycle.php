<?php
/**
* 文章回收站控制器文件
*
* @version        $Id: article.article.recycle.php 2016年4月24日 16:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$articleSer = AdminNewClass('article.article');

//接受post数据
$tid = Request('tid');
$name = Request('name');
$attr = Request('attr');
$tname = Request('tname');

//所有属性
$attrArr = $articleSer->GetAttr();


if( $orderField == '' )
{
	$where['order'] = 'article_id desc';
}

//获取列表条件
$where['table'] = '@article_article as a';
$where['where']['article_status'] = 2;


//判断是否搜索标题
if( $name != '' )
{
	$where['where']['article_name'] = array('like',$name);
}
else
{
	$name = '请输入标题关键字';
}
//判断是否搜索分类
if( $tid != '' )
{
	$where['where']['a.type_id'] = $tid;
}
//判断是否搜索属性
if( $attr != '' )
{
	$where['where']['article_'.$attr] = 1;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@article_type as t'] = 'a.type_id=t.type_id';
$dataArr = wmsql::GetAll($where);

//所有分类
$wheresql['table'] = '@article_type';
$typeArr = wmsql::GetAll($wheresql);
?>