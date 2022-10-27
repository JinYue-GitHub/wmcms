<?php
/**
* 专题页面列表控制器文件
*
* @version        $Id: operate.zt.list.php 2016年5月9日 22:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ztSer = AdminNewClass('operate.zt');

//接受post数据
$name = Request('name');
$tid = Request('tid');

if( $orderField == '' )
{
	$where['order'] = 'zt_id desc';
}

//获取列表条件
$where['table'] = '@zt_zt as zt';

//判断是否搜索标题
if( $name != '' )
{
	$where['where']['zt_name'] = array('like',$name);
}
//判断是否搜索分类
if( $tid != '' )
{
	$where['where']['zt.type_id'] = $tid;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$where['left']['@zt_type as t'] = 'zt.type_id=t.type_id';
$dataArr = wmsql::GetAll($where);
?>