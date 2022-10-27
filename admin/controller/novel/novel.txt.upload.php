<?php
/**
* 导入小说TXT控制器文件
*
* @version        $Id: novel.txt.upload.php 2019年02月20日 16:16  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$novelMod = NewModel('novel.novel');
//接受数据
$nid = Get('nid');
$data = array();

//如果小说id大于0
if ( $nid > '0')
{
	$data = $novelMod->GetOne($nid);
}
?>