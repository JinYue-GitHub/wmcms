<?php
/**
* 应用编辑控制器文件
*
* @version        $Id: app.app.edit.php 2016年5月16日 18:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('app.type');
$firmsSer = AdminNewClass('app.firms');
$attrSer = AdminNewClass('app.attr');
$appConfig = AdminInc('app');

//查询所有分类
$typeArr = $typeSer->GetType();
//语言
$lAttrArr = $attrSer->GetAttr('l');
//资费
$cAttrArr = $attrSer->GetAttr('c');
//平台
$pAttrArr = $attrSer->GetAttr('p');


//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


$picArr = array();
$size = 0;
//如果id大于0
if ( $type == 'edit')
{
	$where['field']['a.*,t.*'] = '';
	$where['field']['au.firms_name as au_name,pa.firms_name as pa_name'] = '';
	$where['table'] = '@app_app as a';
	$where['left']['@app_type as t'] = 't.type_id=a.type_id';
	$where['left']['@app_firms as au'] = "a.app_aid = au.firms_id";
	$where['left']['@app_firms as pa'] = "a.app_oid = pa.firms_id";
	$where['where']['app_id'] = $id;
	
	$data = wmsql::GetOne($where);
	
	$wheresql['table'] = '@upload';
	$wheresql['where']['upload_module'] = 'app';
	$wheresql['where']['upload_cid'] = $id;
	$picArr = wmsql::GetAll($wheresql);
	if( $picArr )
	{
		foreach ($picArr as $k=>$v)
		{
			$size = $size + $v['upload_size'];
		}
	}
	else
	{
		$picArr = array();
	}
}
//不存在就设置默认值
else
{
	$data['app_lid'] = $data['app_cid'] = $data['app_paid'] = '';
	$data['app_status'] = $appConfig['admin_add'];
	$data['app_simg'] = $appConfig['default_simg'];
	$data['app_ico'] = $appConfig['default_ico'];
	$data['app_read'] = $data['app_replay'] = $data['app_ding'] = $data['app_cai'] = 0;
	$data['app_downnum'] = $data['app_start'] = $data['app_score'] = $data['app_rec'] = 0;
	$data['app_addtime'] = time();
}
?>