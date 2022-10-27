<?php
/**
* 信息编辑控制器文件
*
* @version        $Id: about.about.edit.php 2016年4月16日 23:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');
$typeSer = AdminNewClass('about.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@about_about as a';
	$where['left']['@about_type as t'] = 't.type_id=a.type_id';
	$where['where']['about_id'] = $id;

	$data = wmsql::GetOne($where);
	
	//查询使用的模版信息
	$temp['cname'] = $tempSer->GetTemp( $data['about_ctempid'] , 'temp_name' );
}
//不存在就设置默认值
else
{
	$data['about_time'] = time();
}
?>