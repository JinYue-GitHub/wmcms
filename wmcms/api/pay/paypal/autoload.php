<?php
$autoload = WMVENDOR.'autoload.php';
if( !file_exists($autoload) )
{
	die('PayPal支付需到官网下载Vendor扩展包');
}
require $autoload;
require 'config.php';

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;


//电脑支付统一方法
class UnifiedPay{
	private $config;
	private $client;
	/**
	 * 构造方法
	 * @param 参数1，选填，是支付pay还是回调notify
	 */
	function __construct($type='pay')
	{
		$this->config = new PayPalConfig();
		
		$apiContext = new ApiContext(new OAuthTokenCredential($this->config->clientId,$this->config->clientSecret));
		$apiContext->setConfig($this->config->config);
		$this->client = $apiContext;
	}
	function SetNotifyUrl($url = ''){}
	function SetReturnUrl($url = ''){}

	/**
	 * 下单方法
	 * @param 参数1，必须，订单数据
	 */
	function Order($orderData)
	{
		//PC支付，可以根据define延伸WAP支付
		return $this->CreatePcOrder($orderData);
	}
	//PC支付
	private function CreatePcOrder($orderData)
	{
		//设置支付方式
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		//设置货币和支付金额,默认为USD美元支付不支持CNY
		$amount = new Amount();
		$amount->setCurrency("USD")->setTotal(round($orderData['money']/8,2));

		//设置订单信息
		$transaction = new Transaction();
		$transaction->setAmount($amount)->setDescription($orderData['body'])->setInvoiceNumber(uniqid());

		/**
		 * 回调
		 * 当支付成功或者取消支付的时候调用的地址
		 * success=true   支付成功
		 * success=false  取消支付
		 */
		$randStr = str::RandStr(6);
		$sign = $this->GetSign($orderData['sn'],$randStr);
		$params['pay_type'] = 'paypal';
		$params = urlencode(json_encode($params));
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($this->config->returnUrl."?orderSn={$orderData['sn']}&randStr={$randStr}&sign={$sign}&params={$params}")
					 ->setCancelUrl($this->config->cancelUrl);

		//设置支付信息
		$payment = new Payment();
		$payment->setIntent("sale")
				->setPayer($payer)
				->setRedirectUrls($redirectUrls)
				->setTransactions(array($transaction));
				
		//创建支付
		$payment->create($this->client);
		
		//生成地址
		$approvalUrl = $payment->getApprovalLink();
		header("Location: ". $approvalUrl);
		die();
	}

	/**
	 * 同步通知验证
	 * @return multitype:number Ambigous <mixed, boolean, NULL, unknown, string> |multitype:number NULL Ambigous <mixed, boolean, NULL, unknown, string> |multitype:number NULL unknown Ambigous <mixed, boolean, NULL, unknown, string>
	 */
	function ReturnVer()
	{
		$result = array('code'=>500,'msg'=>GetLang('system.finance.pay_fail'));
		$sign = Get('sign');
		$randStr = Get('randStr');
		$orderSn = Get('orderSn');
		$PayerID = Get('PayerID');
		$paymentId = Get('paymentId');

		//参数全部为空
		if ( empty($sign) || empty($randStr) || empty($orderSn) || empty($PayerID) || empty($paymentId) )
		{
			return $result;
		}
		//签名没有通过
		else if( $this->CheckSign($orderSn,$randStr,$sign) == false )
		{
			$result['msg'] = GetLang('system.finance.pay_fail_sign');
			return $result;
		}
		else
		{
			try
			{
				$payment = Payment::get($paymentId, $this->client);
				$execute = new PaymentExecution();
				$execute->setPayerId($PayerID);
				$payment->execute($execute, $this->client);
			}
			//抛出异常
			catch (Exception $e)
			{
				$result['msg'] = $e->getMessage();
				return $result;
			}
			//验证通过
			$result['out_trade_no'] = $orderSn;
			$result['trade_no'] = $paymentId;
			$result['code'] = 0;
			$result['msg'] = GetLang('system.finance.pay_success');
			return $result;
		}
	}
	
	/**
	 * 获得签名
	 * @param 订单号 $orderId
	 * @param 随机字符串 $randStr
	 * @return string
	 */
	function GetSign($orderSn,$randStr)
	{
		$str = $orderSn.$randStr.GetEStr();
		$salt = $randStr.GetEStr();
		return str::E($str,$salt);
	}
	/**
	 * 验证签名
	 * @param 订单号 $orderId
	 * @param 随机字符串 $randStr
	 * @param 验证的签名 $sign
	 * @return boolean
	 */
	function CheckSign($orderSn,$randStr,$sign)
	{
		if( $this->GetSign($orderSn,$randStr) != $sign )
		{
			return false;
		}
		return true;
	}
}
?>