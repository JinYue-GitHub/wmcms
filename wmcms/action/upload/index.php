<?php
/**
* 全系统上传相关功能参数验证处理器
*
* @version        $Id: index.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2019年07月13日 09:50  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//接受参数
$cid = 0;
$mid = 0;
$uid = user::GetUid();
//必须登录
str::EQ( $uid , 0 , $lang['upload']['no_login'] );

$data['user_id'] = $uid;
//不需要检查上传数据
$noCheckUpload = array('del');
if ( in_array($type, $noCheckUpload) )
{
	require_once $type.'.php';
}
else
{
	//允许接受的上传操作的类型
	$typeArr = array( 'userhead' , 'bbspost' , 'articleedit', 'bbsreplay','author','novelcover','articlesimg','replay');
	//上传默认路径
	$upPath = '../../upload/';
	//图片保存路径
	$imgPath = 'images';
	//设置文件控件的名字
	$fileBtnName = 'hide_button';
	//设置保存文件夹
	$filePath = $upPath.$imgPath;
	//允许上传的类型
	$uploadType = $C['config']['web']['upload_type'];
	//允许上传的大小
	$uploadSize = $C['config']['web']['upload_size'];

	//是否剪裁图片
	$uploadCut = $C['config']['web']['upload_cut'];
	//上传图片达到多少宽度需要进行剪裁
	$imgWidth = $C['config']['web']['upload_imgwidth'];
	$imgHeight = $C['config']['web']['upload_imgheight'];
	//图片是否剪裁覆盖原图
	$cutCopy = 0;

	//是否开启水印
	$waterMark = $C['config']['web']['watermark_open'];
	//启用水印后，需要图片宽高大于多少才进行增加水印
	$WMWidth = $C['config']['web']['watermark_width'];
	$WMHeight = $C['config']['web']['watermark_height'];
	//水印类型
	$waterType = $C['config']['web']['watermark_type'];
	//水印位置
	$waterLocation = $C['config']['web']['watermark_location'];
	//水印图片地址
	$waterImg = '../../files/images/watermark.png';
	//文字水印内容
	$waterText = $C['config']['web']['watermark_text'];
	//文字大小
	$waterTextSize = $C['config']['web']['watermark_size'];
	//文字颜色
	$waterTextColor = $C['config']['web']['watermark_color'];
	//附件描述
	$alt = '';

	//是否关闭了上传
	if( GetKey($C,'config,web,upload_open') != '1' )
	{
		ReturnData($lang['upload']['close'] , $ajax);
	}
	else if( !is_array(GetKey($_FILES,$fileBtnName)) )
	{
		ReturnData($lang['upload']['no_data'] , $ajax);
	}
	else if ( in_array($type, $typeArr) )
	{
		require_once $type.'.php';
		require_once 'upload.php';
	}
	else
	{
		ReturnData($lang['upload']['type_no'] , $ajax);
	}
}
?>