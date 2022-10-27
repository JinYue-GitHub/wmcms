<?php
/**
* 小说列表控制器文件
*
* @version        $Id: novel.novel.list.php 2016年4月28日 9:54  weimeng
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


if( $orderField == '' )
{
	$where['order'] = 'novel_id desc';
}

//获取列表条件
$where['table'] = '@novel_novel as n';
$where['left']['@novel_type as t'] = 'n.type_id=t.type_id';
$where['where']['novel_status'] = array('<=','1');


//判断是否搜索标题
if( $name != '' )
{
	switch ($st)
	{
		case '1':
			$where['where']['novel_name'] = array('like',$name);
			break;
			
		case '2':
			$where['where']['novel_author'] = $name;
			break;
			
		case '3':
			$where['where']['novel_id'] = $name;
			break;
	}
}
//判断是否搜索分类
if( $tid != '' )
{
	$where['where']['type_pid'] = array('and-or',array('rin',$tid),array('t.type_id'=>$tid));
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@novel_rec as r'] = 'rec_nid=novel_id';
$dataArr = wmsql::GetAll($where);

//所有分类
$wheresql['table'] = '@novel_type';
$typeArr = wmsql::GetAll($wheresql);
?>