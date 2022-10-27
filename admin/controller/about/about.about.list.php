<?php
/**
* 信息列表控制器文件
*
* @version        $Id: about.about.list.php 2016年5月13日 17:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('about.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受post数据
$tid = Request('tid');
$name = Request('name');
$tname = Request('tname');


if( $orderField == '' )
{
	$where['order'] = 'about_id desc';
}

//获取列表条件
$where['table'] = '@about_about as a';
$where['left']['@about_type as t'] = 'a.type_id=t.type_id';

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['about_name'] = array('like',$name);
}
else
{
	$name = '请输入标题关键字';
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
$dataArr = wmsql::GetAll($where);
?>