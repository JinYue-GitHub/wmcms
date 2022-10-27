<?php
/**
* 上传表处理文件
*
* @version        $Id: upload.upload.php 2016年5月15日 15:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年4月25日 16:35 weimeng
*
*/
$table = '@upload';

//删除文件操作
if( $type == 'del' )
{
	$id = Request('id/i');
	if( $id == '' )
	{
		Ajax('ID错误',300);
	}
	else
	{
		wmsql::Delete($table,array('upload_id'=>$id));
		Ajax('删除成功');
	}
}
?>