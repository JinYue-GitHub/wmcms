<?php
/**
* 小说编辑控制器文件
*
* @version        $Id: novel.novel.edit.php 2016年4月16日 23:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$novelSer = AdminNewClass('novel.novel');
$typeSer = AdminNewClass('novel.type');
$conSer = AdminNewClass('system.config');
$novelConfig = AdminInc('novel');

//查询所有分类
$typeArr = $typeSer->GetType();
//所有推荐属性
$recArr = $novelSer->GetRec();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@novel_novel as n';
	$where['left']['@novel_type as t'] = 't.type_id=n.type_id';
	$where['left']['@novel_rec as r'] = 'novel_id=rec_nid';
	$where['where']['novel_id'] = $id;

	$data = wmsql::GetOne($where);
	$data['novel_name'] = str::DelHtml($data['novel_name']);
}
//不存在就设置默认值
else
{
	$data['novel_status'] = $novelConfig['admin_novel_add'];
	$data['novel_cover'] = $novelConfig['cover'];
	$data['novel_type'] = $novelConfig['type'];
	$data['novel_newcname'] = $novelConfig['new_cname'];
	$data['novel_process'] = 1;
	
	//点击量
	$data['novel_wordnumber'] = $data['novel_allclick'] = $data['novel_score'] = $data['novel_ding']
	= $data['novel_cai'] = $data['novel_replay'] = $data['novel_todayclick'] = $data['novel_weekclick'] 
	= $data['novel_monthclick'] = $data['novel_yearclick'] = $data['novel_todaycoll'] = $data['novel_weekcoll'] 
	= $data['novel_monthcoll'] = $data['novel_yearcoll'] = $data['novel_allcoll'] = $data['novel_todayrec'] 
	= $data['novel_weekrec'] = $data['novel_monthrec'] = $data['novel_yearrec'] = $data['novel_allrec'] 
	= $data['novel_allcoll'] = $data['novel_newcid'] = 0;
	//推荐属性
	$data['author_id'] = $data['rec_icr'] = $data['rec_ibr'] = $data['rec_ir'] = $data['rec_ccr'] = $data['rec_cbr'] = $data['rec_cr'] = 0;
	
	//时间
	$data['novel_clicktime'] = $data['novel_colltime'] = $data['novel_createtime'] 
	= $data['novel_rectime'] = $data['novel_uptime'] = time();
}
?>