<?php
/**
* 用户充值
*
* @version        $Id: charge_code.php 2017年7月19日 21:04  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$ClassArr = array('qrcode');
//引入模块公共文件
require_once 'user.common.php';

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
//二维码是否为空
$code = str::IsEmpty(Request('code'),$lang['user']['no_code']);
$sn = str::IsEmpty(Request('sn'),$lang['user']['no_sn']);
//检查订单号是否存在
$orderMod = NewModel('finance.finance_order');
$order = $orderMod->GetChargeOrderBySn($sn);
if(!$order)
{
	tpl::ErrInfo($lang['user']['no_order']);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'user_charge_code' ,
	'dtemp'=>'user/charge_code.html',
	'label'=>'userlabel',
	'order'=>$order,
	'code'=>$code,
	'sn'=>$sn,
	'label_fun'=>'ChargeCodeLabel',
));

//设置seo信息
tpl::GetSeo();


//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>