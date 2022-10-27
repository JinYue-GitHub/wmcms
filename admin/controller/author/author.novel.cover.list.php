<?php
/**
* 待审封面列表控制器文件
*
* @version        $Id: author.novel.cover.list.php 2017年1月15日 15:47  weimeng
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
			$filed = 'novel_name';
			break;

		case '2':
			$filed = 'novel_author';
			break;
			
		default:
			$filed = 'novel_id';
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
$where['left']['@novel_novel as n'] = 'novel_id=apply_cid';
$where['left']['@manager_manager'] = 'manager_id=apply_manager_id';
$where['left']['@editor'] = 'editor_id=apply_editor_id';
$where['where']['apply_module'] = 'author';
$where['where']['apply_type'] = 'novel_cover';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>