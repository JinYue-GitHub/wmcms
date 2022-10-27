<?php
/**
* 伪静态设置控制器文件
*
* @version        $Id: system.seo.html_plan.list.php 2019年02月27日 14:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$planMod = NewModel('system.plan');
$where = array();
if( $orderField == '' )
{
	$where['order'] = 'plan_id desc';
}
$where = array_merge($where , GetListWhere($where));

//数据条数
$total = $planMod->GetCount();
//列表
$list = $planMod->GetList($where);
?>