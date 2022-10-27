<?php
/**
* 销售报表记录控制器文件
*
* @version        $Id: finance.report.list.php 2021年08月07日 10:08  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$config = GetModuleConfig('user');
$logMod = NewModel('user.finance_log');
$repostMod = NewModel('finance.finance_report');
//可搜索的模块
$moduleList = GetModuleName('all.message.link.user.zt.about.diy.down.replay.search');

//接受post数据
$cid = Request('cid');
$module = Request('module');
$settlement = Request('settlement');
$ids = Request('ids');


//获取列表条件
$where['table'] = '@finance_report';
$where['field'] = '@finance_report.*,user_nickname';
$where['left']['@user_user'] = 'report_user_id=user_id';
if( $orderField == '' )
{
	$where['order'] = 'report_id desc';
}
//判断是否搜索内容id和模块
if( $ids != '' )
{
	$where['where']['report_id'] = array('lin',$ids);
}
if( $cid != '' )
{
	$where['where']['report_cid'] = $cid;
}
if( $module != '' )
{
	$where['where']['report_module'] = $module;
}
if( $settlement != '' )
{
	$where['where']['report_settlement'] = $settlement;
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>