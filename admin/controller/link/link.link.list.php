<?php
/**
* 友链列表控制器文件
*
* @version        $Id: link.link.list.php 2016年4月18日 9:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('link.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受post数据
$tid = Request('tid');
$name = Request('name');
$attr = Request('attr');
$tname = Request('tname');


if( $orderField == '' )
{
	$where['order'] = 'link_id desc';
}

//获取列表条件
$where['table'] = '@link_link as l';
$where['where']['link_status'] = array('<=','1');


//判断是否搜索标题
if( $name != '' )
{
	$where['where']['link_name'] = array('like',$name);
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
//判断是否搜索属性
if( $attr != '' )
{
	$where['where']['link_'.$attr] = 1;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@link_type as t'] = 'l.type_id=t.type_id';
$dataArr = wmsql::GetAll($where);
?>