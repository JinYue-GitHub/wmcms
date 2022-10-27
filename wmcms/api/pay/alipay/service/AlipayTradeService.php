<?php
/* *
 * 功能：支付宝电脑网站支付
 * 版本：2.0
 * 修改日期：2017-05-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
class AlipayTradeService {
	//支付宝网关地址
	public $gateway_url = "https://openapi.alipay.com/gateway.do";
	//支付宝公钥
	public $alipay_public_key;
	//商户私钥
	public $private_key;
	//应用id
	public $appid;
	public $notify_url;
	public $return_url;
	//编码格式
	public $charset = "UTF-8";
	public $token = NULL;
	//返回数据格式
	public $format = "json";
	//签名方式
	public $signtype = "RSA2";

	function __construct($alipay_config){
		$this->gateway_url = $alipay_config['gatewayUrl'];
		$this->appid = $alipay_config['app_id'];
		$this->private_key = $alipay_config['merchant_private_key'];
		$this->alipay_public_key = $alipay_config['alipay_public_key'];
		$this->charset = $alipay_config['charset'];
		$this->signtype=$alipay_config['sign_type'];
		$this->notify_url = $alipay_config['notify_url'];
		$this->return_url = $alipay_config['return_url'];

		if(empty($this->appid)||trim($this->appid)==""){
			throw new Exception("appid should not be NULL!");
		}
		if(empty($this->private_key)||trim($this->private_key)==""){
			throw new Exception("private_key should not be NULL!");
		}
		if(empty($this->alipay_public_key)||trim($this->alipay_public_key)==""){
			throw new Exception("alipay_public_key should not be NULL!");
		}
		if(empty($this->charset)||trim($this->charset)==""){
			throw new Exception("charset should not be NULL!");
		}
		if(empty($this->gateway_url)||trim($this->gateway_url)==""){
			throw new Exception("gateway_url should not be NULL!");
		}

	}

	/**
	 * 电脑支付：alipay.trade.page.pay
	 * @param $data 业务参数，订单的基本信息。
	 * @return $response 支付宝返回的信息
 	*/
	function pagePay($orderData){
		require_once WMAPI.'pay/alipay/request/AlipayTradePagePayRequest.php';
		require_once WMAPI.'pay/alipay/buildermodel/AlipayTradePagePayContentBuilder.php';
		
		$builder = new AlipayTradePagePayContentBuilder();
		$builder->setBody($orderData['body']);
		$builder->setSubject($orderData['subject']);
		$builder->setTotalAmount($orderData['money']);
		$builder->setOutTradeNo($orderData['sn']);
		//设置附加参数
		$params = array();
		if( isset($orderData['params']) )
		{
			$params = $orderData['params'];
		}
		$params['pay_type'] = 'alipay';
		$builder->SetPassbackParams(json_encode($params));
		
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
		
		$request = new AlipayTradePagePayRequest();
		$request->setNotifyUrl($this->notify_url);
		$request->setReturnUrl($this->return_url);
		$request->setBizContent ( $biz_content );

		// 首先调用支付api
		$response = $this->aopclientRequestExecute($request,true);
		// $response = $response->alipay_trade_wap_pay_response;
		return $response;
	}

	/**
	 * 移动wap支付：alipay.trade.wap.pay
	 * @param $data 业务参数，订单的基本信息。
	 * @return $response 支付宝返回的信息
	 */
	function wapPay($orderData){
		require_once WMAPI.'pay/alipay/request/AlipayTradeWapPayRequest.php';
		require_once WMAPI.'pay/alipay/buildermodel/AlipayTradeWapPayContentBuilder.php';
	
		$builder = new AlipayTradeWapPayContentBuilder();
		$builder->setBody($orderData['body']);
		$builder->setSubject($orderData['subject']);
		$builder->setTotalAmount($orderData['money']);
		$builder->setOutTradeNo($orderData['sn']);
		//设置附加参数
		$params = array();
		if( isset($orderData['params']) )
		{
			$params = $orderData['params'];
		}
		$params['pay_type'] = 'alipay_wap';
		$builder->SetPassbackParams(json_encode($params));
	
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
	
		$request = new AlipayTradeWapPayRequest();
		$request->setNotifyUrl($this->notify_url);
		$request->setReturnUrl($this->return_url);
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute($request,true);
		return $response;
	}
	

	//APP支付
	/**
	 * 移动wap支付：alipay.trade.wap.pay
	 * @param $data 业务参数，订单的基本信息。
	 * @return $response 支付宝返回的信息
	 */
	function appPay($orderData){
		require_once WMAPI.'pay/alipay/request/AlipayTradeAppPayRequest.php';
		require_once WMAPI.'pay/alipay/buildermodel/AlipayTradeAppPayContentBuilder.php';
	
		$builder = new AlipayTradeAppPayContentBuilder();
		$builder->setBody($orderData['body']);
		$builder->setSubject($orderData['subject']);
		$builder->setTotalAmount($orderData['money']);
		$builder->setOutTradeNo($orderData['sn']);
		//设置附加参数
		$params = array();
		if( isset($orderData['params']) )
		{
			$params = $orderData['params'];
		}
		$params['pay_type'] = 'alipay_app';
		$builder->SetPassbackParams(json_encode($params));
	
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
	
		$request = new AlipayTradeAppPayRequest();
		$request->setNotifyUrl($this->notify_url);
		$request->setReturnUrl($this->return_url);
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute($request,'sdk');
		return $response;
	}
	
	/**
	 * sdkClient
	 * @param $request 接口请求参数对象。
	 * @param $ispage  是否是页面接口，电脑网站支付是页面表单接口。
	 * @return $response 支付宝返回的信息
 	*/
	function aopclientRequestExecute($request,$ispage=false) {
		$aop = new AopClient ();
		$aop->gatewayUrl = $this->gateway_url;
		$aop->appId = $this->appid;
		$aop->rsaPrivateKey =  $this->private_key;
		$aop->alipayrsaPublicKey = $this->alipay_public_key;
		$aop->apiVersion ="1.0";
		$aop->postCharset = $this->charset;
		$aop->format= $this->format;
		$aop->signType=$this->signtype;
		// 开启页面信息输出
		$aop->debugInfo=true;
		if($ispage === true)
		{
			$result = $aop->pageExecute($request,"post");
		}
		else if($ispage == 'sdk')
		{
			$result = $aop->sdkExecute($request);
		}
		else 
		{
			$result = $aop->Execute($request);
		}
		//打开后，将报文写入log文件
		$this->writeLog("response: ".var_export($result,true));
		return $result;
	}

	/**
	 * alipay.trade.query (统一收单线下交易查询)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
 	*/
	function Query($builder){
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
		$request = new AlipayTradeQueryRequest();
		$request->setBizContent ( $biz_content );

		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_query_response;
		return $response;
	}
	
	/**
	 * alipay.trade.refund (统一收单交易退款接口)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function Refund($builder){
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
		$request = new AlipayTradeRefundRequest();
		$request->setBizContent ( $biz_content );
	
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_refund_response;
		return $response;
	}

	/**
	 * alipay.trade.close (统一收单交易关闭接口)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function Close($builder){
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
		$request = new AlipayTradeCloseRequest();
		$request->setBizContent ( $biz_content );
	
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_close_response;
		return $response;
	}
	
	/**
	 * 退款查询   alipay.trade.fastpay.refund.query (统一收单交易退款查询)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function refundQuery($builder){
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
		$request = new AlipayTradeFastpayRefundQueryRequest();
		$request->setBizContent ( $biz_content );
	
		$response = $this->aopclientRequestExecute ($request);
		return $response;
	}
	/**
	 * alipay.data.dataservice.bill.downloadurl.query (查询对账单下载地址)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function downloadurlQuery($builder){
		$biz_content=$builder->getBizContent();
		//打印业务参数
		$this->writeLog($biz_content);
		$request = new alipaydatadataservicebilldownloadurlqueryRequest();
		$request->setBizContent ( $biz_content );
	
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_data_dataservice_bill_downloadurl_query_response;
		return $response;
	}

	/**
	 * 验签方法
	 * @param $arr 验签支付宝返回的信息，使用支付宝公钥。
	 * @return boolean
	 */
	function check($arr){
		$aop = new AopClient();
		$aop->alipayrsaPublicKey = $this->alipay_public_key;
		$result = $aop->rsaCheckV1($arr, $this->alipay_public_key, $this->signtype);

		return $result;
	}
	
	/**
	 * 请确保项目文件有可写权限，不然打印不了日志。
	 * @param 日志内容
	 * @remark 请确保引入了file类。
	 */
	function writeLog($text,$fileName = 'order') {
		// $text=iconv("GBK", "UTF-8//IGNORE", $text);
		//$text = characet ( $text );
		$fileName =  WMCACHE."log/pay/".alipayConfig::$payType."/".date ("Y-m")."/{$fileName}_".date ("Y-m-d")."_".GetEStr().".txt";
		$fileContent = date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n\r\n";
		file::CreateFile($fileName, $fileContent);
	}
}

?>