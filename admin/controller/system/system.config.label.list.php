<?php
/**
* 自定义标签控制器文件
*
* @version        $Id: system.config.label.list.php 2016年5月20日 21:28  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$name = Request('name');


//获取列表条件
$where['table'] = '@config_label';
if( $orderField == '' )
{
	$where['order'] = 'label_id desc';
}

//判断搜索的类型
if( $name != '' )
{
	$where['where']['label_title'] = array('like',$name);
}
//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$configArr = wmsql::GetAll($where);
?>