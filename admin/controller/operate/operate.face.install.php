<?php
/**
* 表情管理控制器文件
*
* @version        $Id: operate.face.install.php 2017年5月10日 21:33  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$faceSer = AdminNewClass('operate.face');
$faceArr = $faceSer->GetFaceArr();

$replayInstall = $faceSer->GetFaceInstall('replay');
$editorInstall = $faceSer->GetFaceInstall('editor');
?>