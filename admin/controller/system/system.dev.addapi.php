<?php
/**
* 新增API接口配置工具
*
* @version        $Id: system.dev.addapi.php 2018年09月10日 20:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//查询API类型
$where['table'] = '@api_type';
$where['order'] = 'type_order';
$typeArr = wmsql::GetAll($where);
?>