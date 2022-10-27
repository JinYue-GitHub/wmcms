<?php
/**
* 文章作者来源查找待会控制器
*
* @version        $Id: article.author.lookup.php 2016年4月24日 14:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//上传的模块
$module = Get('module');

//点击的文本框id和name
$st = Get('st');
$key = Post('key');

//设置条件
$where['table'] = '@article_author';
$where['where']['author_type'] = $st;
//判断搜索的关键字
if ( $key != '' ) 
{
	$where['where']['author_name'] = array('like',$key);
}
if( $orderField == '' )
{
	$where['order'] = 'author_data desc';
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$data = wmsql::GetAll($where);
?>