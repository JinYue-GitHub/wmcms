<?php
/**
* 作者列表控制器文件
*
* @version        $Id: author.author.list.php 2017年1月11日 20:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$id = Request('id');
$type = Request('type');
$name = Request('name');


if( $orderField == '' )
{
	$where['order'] = 'author_id desc';
}
$typeArr = array('aid'=>"作者id",'aname'=>"作者笔名",'uid'=>"用户id",'uname'=>"用户昵称");
//判断是否搜索标题
if( $name != '' )
{
	switch ($type)
	{
		case 'aname':
			$filed = 'author_nickname';
			break;
			
		case 'uid':
			$filed = 'a.user_id';
			break;
			
		case 'uname':
			$filed = 'user_nickname';
			break;
			
		default:
			$filed = 'author_id';
			break;
	}
	$where['where'][$filed] = array('like',$name);
}
else
{
	$name = '请输入标题关键字';
}


//获取列表条件
$where['table'] = '@author_author as a';
$where['left']['@user_user as u'] = 'a.user_id=u.user_id';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>