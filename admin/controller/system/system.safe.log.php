<?php
/**
* 管理员账号登录记录
*
* @version        $Id: system.safe.log.php 2016年4月6日 15:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入类文件
$safeSer = AdminNewClass('system.safe');

//获取列表条件
$where['table'] = '@manager_login as l';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where['left']['@manager_manager as m'] = 'l.manager_id=m.manager_id';
$where = GetListWhere($where);

if( $where['order'] == '')
{
	$where['order'] = 'login_id desc';
}
$logArr = wmsql::GetAll($where);

//循环判断当前数据
if( $logArr )
{
	foreach ($logArr as $k=>$v)
	{
		$logArr[$k]['login_status_text'] = $safeSer->GetAdminStatus($v['login_status']);
		if( $v['manager_cid'] == '0' )
		{
			$logArr[$k]['comp_name'] = '超级管理员';
		}
	}
}
?>