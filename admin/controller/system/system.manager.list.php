<?php
/**
* 管理员账号列表控制器文件
*
* @version        $Id: system.admin.list.php 2016年4月6日 11:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入类文件
$managerSer = AdminNewClass('system.manager');


//获取列表条件
$where['table'] = '@manager_manager';

//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where['left']['@system_competence'] = 'comp_id=manager_cid';
$where['where']['manager_cid'] = array('<>',0);
$where = GetListWhere($where);

$managerArr = wmsql::GetAll($where);

//循环判断当前数据
if( $managerArr )
{
	foreach ($managerArr as $k=>$v)
	{
		$managerArr[$k]['manager_status_text'] = $managerSer->GetAdminStatus($v['manager_status']);
		if( $v['manager_cid'] == '0' )
		{
			$managerArr[$k]['comp_name'] = '超级管理员';
		}
	}
}
?>