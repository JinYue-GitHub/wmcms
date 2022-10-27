<?php
/**
* 模块域名绑定控制器文件
*
* @version        $Id: system.set.domain.php 2016年3月28日 16:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//查询模块绑定数据
$where['table'] = '@system_domain';
$where['order'] = 'domain_order';
$domainArr = wmsql::GetAll($where);

//获得域名的全部配置
$configArr = str::ArrRestKey($manager->GetConfig( 'domain' ) , 'config_name');
$adminDomainAccess = $configArr['admin_domain_access']['config_value'];
?>