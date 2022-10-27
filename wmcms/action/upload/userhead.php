<?php
/**
* 用户头像上传请求处理
*
* @version        $Id: userhead.php 2015年8月15日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月28日 12:44  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( $uid , 0 , $lang['upload']['no_login'] );

//设置模块
$module = 'user';

//设置图片默认描述
$alt = $lang['upload']['head_alt'];

//允许上传类型
$uploadType = 'jpg,jpeg,png,gif';

//获取用户模块配置
$userConfig = GetModuleConfig('user');
$imgWidth = $userConfig['head_width'];
$imgHeight = $userConfig['head_height'];
//多少尺寸才减裁
C('config.web.upload_imgwidth',$userConfig['head_width']);
C('config.web.upload_imgheight',$userConfig['head_height']);
//剪裁
$uploadCut = 1;
//剪裁后覆盖原图
$cutCopy = 1;
C('config.web.upload_cut',1);
//水印
$waterMark = 0;
C('config.web.watermark_open',0);

//上传完成回调方法
function CallBack($result)
{
    $userMod = NewModel('user.user');
    $userMod->head = $result['file'];
    $result = $userMod->SaveHead(user::GetUid());
}
?>