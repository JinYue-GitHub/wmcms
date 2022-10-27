<?php
/**
* 用户充值完成
*
* @version        $Id: charge_success.php 2017年7月24日 15:34  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
//检查订单号是否存在
$sn = str::IsEmpty(Request('sn',Cookie('order_sn')),$lang['user']['no_sn']);
$orderMod = NewModel('finance.finance_order');
$order = $orderMod->GetChargeOrderBySn($sn);
if(!$order)
{
	tpl::ErrInfo($lang['user']['no_order']);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_charge_success' ,
	'dtemp'=>'user/charge_success.html',
	'label'=>'userlabel',
	'order'=>$order,
	'sn'=>$sn,
	'label_fun'=>'ChargSuccessLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>