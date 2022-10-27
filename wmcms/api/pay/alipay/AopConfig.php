<?php
class alipayConfig{
	static $alipayConfig;
	public static $payType;
	function __construct($payType){
		alipayConfig::$payType = $payType;
		
		alipayConfig::$alipayConfig = array (	
			//APPID、商户私钥
			'app_id' => C('config.api.'.$payType.'.api_appid'),
			'merchant_private_key' => C('config.api.'.$payType.'.api_apikey'),
			//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
			'alipay_public_key' => C('config.api.'.$payType.'.api_secretkey'),
			//异步通知地址、同步跳转
			'notify_url' => DOMAIN.'/wmcms/notify/pay.php',
			'return_url' => DOMAIN.'/module/user/charge_success.php',
			//编码格式、签名方式
			'charset' => "UTF-8",
			'sign_type'=>"RSA2",
			//支付宝网关
			'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		);
	}
}