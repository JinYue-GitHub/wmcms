<?php
/**
* 结算报表处理器
*
* @version        $Id: finance.report.php 2021年08月07日 11:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$reportTable = '@finance_report';
//财务配置
$financeConfig = GetModuleConfig('finance' , true);
$repostMod = NewModel('finance.finance_report');

//获得确认信息
if ( $type == 'confirm'  )
{
	$data = $repostMod->GetSum(GetDelId());
	$data['rmb_to_gold2'] = $financeConfig['rmb_to_gold2'];
	$data['ids'] = is_array(GetDelId())?Request('ids'):Request('id');
	Ajax('','200',$data);
}
//确认结算
else if ( $type == 'settlement')
{
	$where['report_id'] =  array('lin' , Request('ids'));
	SetOpLog( '结算了报表！' , 'finance' , 'update' , $reportTable,$where);
	$repostMod->Settlement($where['report_id'],C('admin_id'));
	Ajax('结算成功！');
}
?>