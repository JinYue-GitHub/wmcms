<?php
/**
* 小说章节列表控制器文件
*
* @version        $Id: novel.chapter.list.php 2016年4月29日 10:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受参数
$st = Request('st');
$name = Request('name');
$nid = Request('nid');
$volume = array();

if( $orderField == '' )
{
	$where['order'] = 'chapter_order desc';
}

//获取列表条件
$where['table'] = '@novel_chapter';
$where['left']['@novel_novel as n'] = 'novel_id=chapter_nid';
$where['left']['@novel_volume'] = 'volume_id=chapter_vid';
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
			$where['where']['chapter_name'] = array('like',$name);
			break;
	}
}
if( $nid != '' )
{
	$where['where']['chapter_nid'] = $nid;
	//获得分卷列表
	$volumeWhere['table'] = '@novel_volume';
	$volumeWhere['where']['volume_nid'] = array('or','0,'.$nid);
	$volumeWhere['order'] = 'volume_order asc';
	$volume = wmsql::GetAll($volumeWhere);
}

//如果是都是为空就查询id为1的小说
if( $nid == '' && $name == '' )
{
	$where['where']['chapter_nid'] = 1;
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
if( !empty($where['order']) )
{
	$where['order'] = 'chapter_nid,'.$where['order'];
}
else
{
	$where['order'] = 'chapter_nid';
}
$dataArr = wmsql::GetAll($where);
?>