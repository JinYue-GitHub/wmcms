<?php
/**
* 资金变更记录控制器文件
*
* @version        $Id: finance.apply.list.php 2018年9月8日 21:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');
$goldName = $userConfig['gold2_name'];

//接受post数据
$name = Request('name');

//获取列表条件
$where['table'] = '@finance_apply as apply';
$where['field'] = 'apply.*,a.manager_name as aname,h.manager_name as hname';
$where['left']['@manager_manager as a'] = 'apply_manager_id=a.manager_id';
$where['left']['@manager_manager as h'] = 'apply_handle_manager_id=h.manager_id';
if( $orderField == '' )
{
	$where['order'] = 'apply_id desc';
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>