<?php
/**
* 论坛分类列表控制器文件
*
* @version        $Id: bbs.type.list.php 2016年5月18日 14:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$open = Request('open');

$typeSer = AdminNewClass('bbs.type');
$moderSer = AdminNewClass('bbs.moder');
$manager = AdminNewClass('manager');


//查询所有分类
$order['order'] = 'type_order';
$typeArr = $typeSer->GetType($order);
if( $typeArr )
{
	foreach ($typeArr as $k=>$v)
	{
		$typeArr[$k]['moder'] = $moderSer->GetModerUids($v['type_id']);
	}
	$typeArr = str::Tree($typeArr, 'type_topid', 'type_id');
}

?>