<?php
/**
* 专题节点页面控制器文件
*
* @version        $Id: operate.zt.node.edit.php 2016年5月10日 11:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$nodeSer = AdminNewClass('operate.zt.node');

//所有模块分类
$typeArr = $nodeSer->GetType();


//接受数据
$zid = Get('zid');
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@zt_node';
	$where['where']['node_id'] = $id;

	$data = wmsql::GetOne($where);
}
//不存在就设置默认值
else
{
	$data['node_type'] = '2';
}
?>