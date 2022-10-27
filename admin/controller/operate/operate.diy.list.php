<?php
/**
* diy页面列表控制器文件
*
* @version        $Id: operate.diy.list.php 2016年5月7日 22:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$diySer = AdminNewClass('operate.diy');

//接受post数据
$name = Request('name');

if( $orderField == '' )
{
	$where['order'] = 'diy_id desc';
}

//获取列表条件
$where['table'] = '@diy_diy';

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['diy_name'] = array('like',$name);
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>