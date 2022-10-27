<?php
/**
* 上传ajax请求处理
*
* @version        $Id: system.php 2015年8月15日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年1月16日 11:44  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//设置上传属性
$upload = NewClass('upload' , $_FILES[$fileBtnName] );
//读取网站的设置上传大小
$upload->set_size( $uploadSize );
//读取网站的设置上传文件类型
$upload->set_extention( $uploadType );
//文件存储根目录名称
$upload->set_base_directory($filePath.'/');
//文件上传成功后跳转的文件
$upload->set_url("index.php");
//保存文件
$result = $upload->save();
$result['alt'] = $alt;

//判断上传是否失败
if ( isset($result['code']) && $result['code'] == '500')
{
	ReturnData($result['msg'] , $ajax);
}


//new上传模型
$uploadMod = NewModel('upload.upload');
//设置参数
$uploadMod->module = $module;
$uploadMod->type = $type;
$uploadMod->cid = $cid;
$uploadMod->mid = $mid;
$uploadMod->uploadData = $result;
//插入上传记录
$result['file_id'] = $uploadMod->Insert();


//如果是图片，就进行剪裁，缩略图等操作
if ( str::IsImg($result['ext']) )
{
	$imgSer = NewClass('img');
	//图片裁剪不覆盖原图
	if( $cutCopy == 0 )
	{
		$imgSer->ImgCut( $result['path'] , '0' , '0', $result['simg']);
	}
	//覆盖原图
	else
	{
		$imgSer->ImgCut( $result['path'] , '0' , '0');
	}
	//图片加水印
	$imgSer->WaterMark( $result['path'] );
}


//如果存在回调函数就执行
if( function_exists('CallBack') )
{
	CallBack($result);
}

//返回上传成功
ReturnData($lang['upload']['operate']['upload']['success'] , $ajax , 200 , $result);
?>