<?php
class PayPalConfig{
	public $returnUrl = '';
	public $cancelUrl = '';
	public $config = array();
	
	function __construct(){
		// Creating an environment
		$this->clientId = C('config.api.paypal.api_appid');
		$this->clientSecret = C('config.api.paypal.api_secretkey');
		
		$this->returnUrl = DOMAIN.'/wmcms/notify/return.php';
		$this->cancelUrl = DOMAIN.'/module/user/charge.php';
	
	
		$filePath = WMCACHE.'log/pay/paypal/'.date("Y-m").'/order_'.date("Y-m-d").'_'.GetEStr().'.txt';
		if( !file_exists($filePath))
		{
			file::CreateFile($filePath, '');
		}
		$this->config = array(
				//sandbox 沙箱模式 live线上模式
				'mode' => 'live',
				//启用日志
				'log.LogEnabled' => true,
				//日志路径
				'log.FileName' => $filePath,
				//日志级别
				'log.LogLevel' => 'DEBUG', 
				'cache.enabled' => true
			);
	}
}