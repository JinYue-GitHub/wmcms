<?php
/**
* 友链编辑控制器文件
*
* @version        $Id: link.link.edit.php 2016年5月13日 13:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('link.type');
$linkConfig = AdminInc('link');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@link_link as a';
	$where['left']['@link_type as t'] = 't.type_id=a.type_id';
	$where['where']['link_id'] = $id;

	$data = wmsql::GetOne($where);
}
//不存在就设置默认值
else
{
	$data['link_status'] = $linkConfig['admin_status'];
	$data['link_jointime'] = time();
	$data['link_fixed'] = $data['link_rec'] = $data['link_read'] = $data['link_ding'] = $data['link_cai'] = 0;
	$data['link_show'] = 1;
	$data['link_order'] = 99;
	$data['link_inday'] = $data['link_inweek'] = $data['link_inmonth'] = $data['link_inyear'] = $data['link_insum'] = 0;
	$data['link_outday'] = $data['link_outweek'] = $data['link_outmonth'] = $data['link_outyear'] = $data['link_outsum'] = 0;

}
?>