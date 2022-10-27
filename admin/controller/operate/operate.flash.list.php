<?php
/**
* 幻灯片列表控制器文件
*
* @version        $Id: operate.flash.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$flashSer = AdminNewClass('operate.flash');

//所有模块分类
$moduleArr = $flashSer->GetModule();

//接受post数据
$module = Request('module');

if( $orderField == '' )
{
	$where['order'] = 'flash_id desc';
}

//获取列表条件
$where['table'] = '@flash_flash as f';

//判断是否搜索标题
if( $module != '' )
{
	$where['where']['flash_module'] = $module;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$where['left']['@flash_type as t'] = 'f.type_id=t.type_id';
$dataArr = wmsql::GetAll($where);
?>