<?php
/**
* 分类检索条件表
*
* @version        $Id: system.retrieval.list.php 2017年6月16日 21:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$name = Request('name');
$tid = Request('tid');
list($module,$st) = explode('_', $type);
if($module == '' )
{
	$module = 'novel';
}
$reMod = NewModel('system.retrieval');
$reSer = AdminNewClass('system.retrieval');
$typeArr = $reSer->GetType($module);


//获取列表条件
$where['where']['retrieval_module'] = $module;
//判断搜索的类型
if( $name != '' )
{
	$where['where']['retrieval_title'] = array('like',$name);
}
if( $tid != '' )
{
	$where['where']['retrieval_type_id'] = $tid;
}
//获取列表条件
if( $orderField == '' )
{
	$where['order'] = 'retrieval_type_id,retrieval_order';
}
//数据条数
$total = $reMod->GetCount($where);
//当前页的数据
$list = $reMod->GetAll(GetListWhere($where));
?>