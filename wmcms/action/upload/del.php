<?php
/**
* 删除用户自己的上传文件方法
*
* @version        $Id: del.php 2019年07月12日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$id = str::Int( Request('id') , $lang['upload']['del_par_err'] );
$module = Request('module','bbs');

//new上传模型
$uploadMod = NewModel('upload.upload');
$uploadMod->where['upload_id'] = $id;
$uploadMod->where['upload_module'] = $module;
$uploadMod->where['user_id'] = user::GetUid();
$uploadData = $uploadMod->GetOne();
//存在数据
if( $uploadData )
{
	//删除文件
	file::DelFile(WMROOT.$uploadData['upload_img']);
	file::DelFile(WMROOT.$uploadData['upload_simg']);
	//删除数据
	$uploadMod->Delete($id);
}
ReturnData($lang['upload']['operate']['del']['success'], $ajax , 200);
?>