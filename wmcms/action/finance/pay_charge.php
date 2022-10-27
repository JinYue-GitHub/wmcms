<?php
/**
* 充值卡使用操作操作
*
* @version        $Id: card_charge.php 2017年4月2日 22:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//使用账号类型
$accountType = str::Int( Request('accounttype' , 0) );
//对好友使用的账号
$account = trim(Request('account'));
//重复
$reAccount = trim(Request('reaccount'));
//充值金额
$money = str::Int(Request('money'));
//支付方式
$payType = str::ClearInclude(trim(Request('paytype','alipay')));
$apiData = C('config.api.'.$payType);

//充值金额是否存在
if( $money <= 0 )
{
	ReturnData( $lang['finance']['action']['pay_money_no'] , $ajax );
}
//如果为好友充值则判断账号是否正确
else if ( $accountType == 1 && ( $account != $reAccount || $account == '' || str::CheckLen( $account , '4,16') == false) )
{
	ReturnData( $lang['finance']['action']['account_err'] , $ajax );
}
//查询充值方式是否存在
else if( $payType == '' || !is_array($apiData) )
{
	ReturnData( $lang['finance']['action']['pay_no'] , $ajax );
}
//已经关闭了
else if( $apiData['api_open'] != '1' )
{
	ReturnData( $lang['finance']['action']['pay_close'] , $ajax );
}
//如果是微信支付就提示必须微信打开
else if( $payType=='wxpays_jsapi' && !IsWeiXin())
{
	ReturnData( $lang['finance']['action']['no_wxbro'] , $ajax );
}
//数据正常
else
{
	//引入公共支付类
	$paySer = NewClass('pay');

	//设置参数
	$data['pay_type'] = $payType;
	$data['uid'] = $uid;
	$data['money'] = $money;
	$data['is_first'] = user::GetIsCharge();
	if( $accountType == '1' )
	{
		$data['account'] = $account;
	}
	$result = $paySer->Order($data);
	if( $result === false )
	{
		ReturnData( $lang['finance']['action']['pay_apino'] , $ajax );
	}
	else if( isset($result['code']) && $result['code'] != '200' )
	{
		ReturnData( $result['msg'] , $ajax );
	}
	else
	{
		//不用支付类型的后置操作。
		switch ($payType)
		{
			//微信JSAPI支付
			case 'wxpay_jsapi':
				?>
				<script>
				//调用微信JS api 支付
				function jsApiCall()
				{
					WeixinJSBridge.invoke(
						'getBrandWCPayRequest',
						<?php echo $result;?>,
						function(res){
							if(res.err_msg == "get_brand_wcpay_request:ok" ){
								window.location.href='<?php echo tpl::url('user_charge_success');?>';
								return;
							}else if(res.err_msg == "get_brand_wcpay_request:cancel" ){
								alert('取消支付');
							}else{
								alert(res.err_code+res.err_desc+res.err_msg);
							}
							window.location.href='<?php echo tpl::url('user_charge');?>';
						}
					);
				}
				function callpay()
				{
					if (typeof WeixinJSBridge == "undefined"){
						if( document.addEventListener ){
							document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
						}else if (document.attachEvent){
							document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
							document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
						}
					}else{
						jsApiCall();
					}
				}
				callpay();
				</script>
				<?php
				die();
				break;
					
			//微信电脑支付，跳转到二维码页面
			case 'wxpay':
				header("Location: ".tpl::Url('user_charge_code',array('sn'=>$paySer->orderSn,'code'=>urlencode(Encrypt($result,'E')))));
				break;

			//各种APP支付
			case 'wxpay_app':
			case 'alipay_app':
				ReturnJson( '', 200 ,$result);
				break;
			//支付宝或者其他的支付方式就直接跳转到支付宝官方网页进行支付操作。
			default:
				die($result);
				break;
		}
	}
}
?>