<?php
/**
* 主题列表控制器文件
*
* @version        $Id: bbs.bbs.list.php 2016年5月16日 18:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$bbsSer = AdminNewClass('bbs.bbs');
$typeSer = AdminNewClass('bbs.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受post数据
$tid = Request('tid');
$name = Request('name');
$attr = Request('attr');
$tname = Request('tname');

//所有属性
$attrArr = $bbsSer->GetAttr();


if( $orderField == '' )
{
	$where['order'] = 'bbs_id desc';
}

//获取列表条件
$where['table'] = '@bbs_bbs as b';
$where['left']['@bbs_type as t'] = "b.type_id = t.type_id";


//判断是否搜索标题
if( $name != '' )
{
	$where['where']['bbs_title'] = array('like',$name);
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
	$where['where']['bbs_'.$attr] = 1;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['filed'] = 'b.*,type_name,user_nickname';
$where['left']['@user_user as u'] = "u.user_id = b.user_id";
$dataArr = wmsql::GetAll($where);
?>