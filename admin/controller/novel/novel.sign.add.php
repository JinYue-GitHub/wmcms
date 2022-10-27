<?php
/**
* 小说签约控制器文件
*
* @version        $Id: novel.sign.add.php 2017年3月12日 13:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$novelMod = NewModel('novel.novel');
$signMod = NewModel('author.sign');

//接受数据
$data = $novelMod->GetOne(Get('nid'));
if( $type == '' )
{
	$type = 'add';
}
//接受数据
$signArr = $signMod->GetAll('novel');
?>