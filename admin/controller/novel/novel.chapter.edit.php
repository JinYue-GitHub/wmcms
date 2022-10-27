<?php
/**
* 小说章节编辑控制器文件
*
* @version        $Id: novel.chapter.edit.php 2016年4月28日 15:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$chapterMod = NewModel('novel.chapter');
$novelConfig = AdminInc('novel');

//接受数据
$id = Get('id');
$nid = Get('nid');
$vidArr = array();
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@novel_chapter as n';
	$where['left']['@novel_novel'] = 'novel_id=chapter_nid';
	$where['where']['chapter_id'] = $id;
	
	$data = wmsql::GetOne($where);
	
	$nid = $data['chapter_nid'];
	$data['content'] = str::ToTxt($chapterMod->GetTxtContent($data['type_id'],$nid,$data['chapter_id'],$data['chapter_istxt']));
}
//不存在就设置默认值
else
{
	if( $nid != '' )
	{
		$where['table'] = '@novel_novel';
		$where['where']['novel_id'] = $nid;
		$data = wmsql::GetOne($where);
	}
	
	//发布状态
	$data['chapter_status'] = $novelConfig['admin_chapter_add'];
	$data['chapter_isvip'] = $novelConfig['chapter_isvip'];
	$data['chapter_order'] = 0;

	//时间
	$data['chapter_time'] = time();
}


if( $nid > 0 || $type == 'edit')
{
	$wheresql['table'] = '@novel_volume';
	$wheresql['where']['volume_nid'] = $nid;
	$wheresql['order'] = 'volume_order desc';
	$vidArr = wmsql::GetAll($wheresql);
}
$vidArr[] = array('volume_id'=>1,'volume_name'=>'正文');
?>