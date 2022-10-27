<?php
/**
* 小说限时免费列表控制器文件
*
* @version        $Id: novel.timelimit.list.php 2018年8月27日 22:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$name = Request('name');
$st = Request('st');
$tname = Request('tname');


if( $orderField == '' )
{
	$where['order'] = 'timelimit_order desc';
}

//获取列表条件
$where['table'] = '@novel_timelimit';
$where['left']['@novel_novel'] = 'timelimit_nid=novel_id';

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
	}
}
else
{
	$name = '请输入搜索关键字';
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>