<?php
/**
* 小说限时免费控制器文件
*
* @version        $Id: novel.timelimit.php 2018年8月27日 21:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$limitMod = NewModel('novel.timelimit');
$novelMod = NewModel('novel.novel');
//接受数据
$id = Get('id');
$nid = Get('nid');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $limitMod->GetById($id);
}
else
{
	if($nid > 0)
	{
		$data = $limitMod->GetByNid($nid);
	}
	if( !$data )
	{
		$data = $novelMod->GetOne($nid);
		$data['timelimit_nid'] = $nid;
		$data['timelimit_starttime'] = time();
		$data['timelimit_order'] = 9;
		$data['timelimit_endtime'] = time()+3600*24*7;
	}
}
?>