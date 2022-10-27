<?php
/**
* 财务申请详情控制器文件
*
* @version        $Id: finance.apply.detail.php 2018年9月8日 21:47  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');
$goldName = $userConfig['gold2_name'];
//财务配置
$financeConfig = GetModuleConfig('finance' , true);

//接受数据
$id = intval(Get('id'));

$where['table'] = '@finance_apply as apply';
$where['field'] = 'apply.*,a.manager_name as aname,h.manager_name as hname,u.user_name,u.user_nickname';
$where['left']['@manager_manager as a'] = 'apply_manager_id=a.manager_id';
$where['left']['@manager_manager as h'] = 'apply_handle_manager_id=h.manager_id';
$where['left']['@user_user as u'] = 'user_id=apply_to_user_id';
$where['where']['apply_id'] = $id;
$data = wmsql::GetOne($where);
?>