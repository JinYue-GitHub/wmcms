<?php
/**
* 登录记录详情
*
* @version        $Id: system.safe.log.detail.php 2016年4月6日 15:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入类文件
$safeSer = AdminNewClass('system.safe');


//接受数据
$id = Get('id');
$data = '';

//如果id大于0
if ( str::Number($id) )
{
	$where['table'] = '@manager_login as l';
	$where['left']['@manager_manager as m'] = 'l.manager_id=m.manager_id';
	$where['where']['login_id'] = $id;

	$data = wmsql::GetOne($where);
	
	$data['login_status_text'] = $safeSer->GetAdminStatus($data['login_status']);
}
?>