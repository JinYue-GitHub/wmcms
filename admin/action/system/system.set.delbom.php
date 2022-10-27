<?php
/**
* 删除bom处理器
*
* @version        $Id: system.delbom.php 2016年3月31日 13:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$path = WMTEMPLATE.Get('path');

//载入删除bom的类
$delBom = NewClass('delbom');
$delBom->checkdir($path);


//写入操作记录
SetOpLog( '删除了BOM头部' , 'system' , 'update');

Ajax('BOM头删除成功！');
?>