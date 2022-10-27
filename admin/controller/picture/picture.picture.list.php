<?php
/**
* 图集列表控制器文件
*
* @version        $Id: picture.picture.list.php 2016年5月15日 10:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$pictureSer = AdminNewClass('picture.picture');

//接受post数据
$tid = Request('tid');
$name = Request('name');
$attr = Request('attr');
$tname = Request('tname');

//所有属性
$attrArr = $pictureSer->GetAttr();


if( $orderField == '' )
{
	$where['order'] = 'picture_id desc';
}

//获取列表条件
$where['table'] = '@picture_picture as p';
$where['left']['@picture_type as t'] = 'p.type_id=t.type_id';
$where['where']['picture_status'] = array('<=','1');


//判断是否搜索标题
if( $name != '' )
{
	$where['where']['picture_name'] = array('like',$name);
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
	$where['where']['picture_'.$attr] = 1;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);

//所有分类
$wheresql['table'] = '@picture_type';
$typeArr = wmsql::GetAll($wheresql);
?>