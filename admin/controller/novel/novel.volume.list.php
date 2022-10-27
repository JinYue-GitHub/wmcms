<?php
/**
* 小说分卷列表控制器文件
*
* @version        $Id: novel.volume.list.php 2016年4月28日 14:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受参数
$st = Request('st');
$name = Request('name');
$nid = Request('nid');

if( $orderField == '' )
{
	$where['order'] = 'volume_id desc';
}
//获取列表条件
$where['table'] = '@novel_volume';
$where['left']['@novel_novel as n'] = 'novel_id=volume_nid';
$where['left']['@novel_type as t'] = 'n.type_id=t.type_id';


//判断是否搜索内容
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
else if( $nid != '' )
{
	$where['where']['volume_nid'] = $nid;
}

if( $name == '' )
{
	$name = '请输入关键字';
}

//数据条数
$total = wmsql::GetCount($where);


//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>