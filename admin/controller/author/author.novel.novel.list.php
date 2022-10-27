<?php
/**
* 原创小说列表控制器文件
*
* @version        $Id: author.novel.novel.list.php 2017年1月15日  17:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$tid = Request('tid');
$name = Request('name');
$st = Request('st');
$tname = Request('tname');


//获取列表条件
$where['table'] = '@novel_novel as n';
$where['where']['author_id'] = array('>',0);
if( $orderField == '' )
{
	$where['order'] = 'novel_id desc';
}

//判断是否搜索标题
if( $name != '' )
{
	switch ($st)
	{
		case '1':
			$where['where']['novel_name'] = array('like',$name);
			break;
			
		case '2':
			$where['where']['novel_author'] = array('like',$name);
			break;
			
		case '3':
			$where['where']['novel_id'] = array('like',$name);
			break;
	}
}
else
{
	$name = '请输入搜索关键字';
}
//判断是否搜索分类
if( $tid != '' )
{
	$where['where']['n.type_id'] = $tid;
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@novel_type as t'] = 'n.type_id=t.type_id';
$where['left']['@novel_rec as r'] = 'rec_nid=novel_id';
$dataArr = wmsql::GetAll($where);

//所有分类
$wheresql['table'] = '@novel_type';
$typeArr = wmsql::GetAll($wheresql);
?>