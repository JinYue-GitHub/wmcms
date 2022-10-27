<?php
/**
* 互动记录列表控制器文件
*
* @version        $Id: operate.operate.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$operateSer = AdminNewClass('operate.operate');
$moduleArr = $operateSer->GetModule();

//接受post数据
$module = Request('module');

if( $orderField == '' )
{
	$where['order'] = 'operate_id desc';
}

//获取列表条件
$where['table'] = '@operate_operate';


//判断是否搜索标题
if( $module != '' )
{
	$where['where']['operate_module'] = $module;
}
//操作类型
if( $type == 'dingcai' )
{
	$where['where']['operate_type'] = array('or','ding,cai');
}
else
{
	$where['where']['operate_type'] = $type;
}

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>