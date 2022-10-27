<?php
/**
* 专题页面列表控制器文件
*
* @version        $Id: operate.zt.node.list.php 2016年5月10日 9:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$nodeSer = AdminNewClass('operate.zt.node');

//接受post数据
$id = Request('id');
$name = Request('name');

if( $orderField == '' )
{
	$where['order'] = 'node_id desc';
}

//获取列表条件
$where['table'] = '@zt_node';
$where['left']['@zt_zt'] = 'node_zt_id=zt_id';
$where['where']['node_zt_id'] = $id;

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['node_name'] = array('like',$name);
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>