<?php
/**
* 图集编辑控制器文件
*
* @version        $Id: picture.picture.edit.php 2016年5月15日 9:52  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('picture.type');
$pictureConfig = AdminInc('picture');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@picture_picture as p';
	$where['left']['@picture_type as t'] = 't.type_id=p.type_id';
	$where['where']['picture_id'] = $id;

	$data = wmsql::GetOne($where);
	
	$wheresql['table'] = '@upload';
	$wheresql['where']['upload_module'] = 'picture';
	$wheresql['where']['upload_cid'] = $id;
	$picArr = wmsql::GetAll($wheresql);
}
//不存在就设置默认值
else
{
	$data['picture_status'] = $pictureConfig['admin_add'];
	$data['picture_read'] = $data['picture_replay'] = $data['picture_ding'] = $data['picture_cai'] = 0;
	$data['picture_start'] = $data['picture_score'] = $data['picture_rec'] = 0;
	$data['picture_time'] = time();
}

if( empty($picArr) )
{
	$size = 0;
	$picArr = array();
}
?>