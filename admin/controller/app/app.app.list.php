<?php
/**
* 应用列表控制器文件
*
* @version        $Id: app.app.list.php 2016年5月16日 18:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$appSer = AdminNewClass('app.app');
$typeSer = AdminNewClass('app.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受post数据
$tid = Request('tid');
$name = Request('name');
$attr = Request('attr');
$tname = Request('tname');

//所有属性
$attrArr = $appSer->GetAttr();


if( $orderField == '' )
{
	$where['order'] = 'app_id desc';
}

//获取列表条件
$where['table'] = '@app_app as a';
$where['left']['@app_type as t'] = "a.type_id = t.type_id";
$where['where']['app_status'] = array('<=','1');


//判断是否搜索标题
if( $name != '' )
{
	$where['where']['app_name'] = array('like',$name);
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
	$where['where']['app_'.$attr] = 1;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['field']['a.*,t.*'] = '';
$where['field']['au.firms_name as au_name,pa.firms_name as pa_name'] = '';
$where['left']['@app_firms as au'] = "a.app_aid = au.firms_id";
$where['left']['@app_firms as pa'] = "a.app_oid = pa.firms_id";
$dataArr = wmsql::GetAll($where);
?>