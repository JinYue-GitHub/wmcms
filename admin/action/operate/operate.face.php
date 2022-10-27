<?php
/**
* 表情处理器
*
* @version        $Id: operate.face.php 2017年5月11日 9:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/

//修改分类信息
if ( $type == 'install' )
{
	$faceSer = AdminNewClass('operate.face');
	$faceArr = $faceSer->GetFaceArr();
	
	//创建评论表情配置
	$faceSer->CreateFaceJson('replay' , GetKey($post,'replay') , $faceArr);
	//创建编辑器表情配置
	$faceSer->CreateFaceJson('editor' , GetKey($post,'editor') , $faceArr);
	
	Ajax('表情配置生成成功！',200);
}
?>