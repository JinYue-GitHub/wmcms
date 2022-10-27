<?php
require_once "lib/WxPay.Api.php";
require_once 'log.php';
//创建配置
new WxPayConfig($payType);

//电脑扫码支付统一方法
class UnifiedPay{
	private $logHandler;
	private $openId;
	/**
	 * 构造方法
	 * @param 参数1，选填，是支付pay还是回调notify
	 */
	function __construct($type='pay')
	{
		//如果是微信js_api支付
		if( defined('WXPAY_JSAPI') )
		{
			$data['appid'] = WxPayConfig::$appid;
			$data['secret'] = WxPayConfig::$appsecret;
			if( $type == 'pay' )
			{
				$wxSer = NewClass('weixin_account',$data);
				$this->openId = $wxSer->GetOpenId();
			}
		}
		//初始化日志
		$this->logHandler= new CLogFileHandler();
		Log::Init($this->logHandler, 15);
	}
	
	/**
	 * 设置异步通知地址
	 * @param 参数1，异步通知地址
	 */
	function SetNotifyUrl($url='')
	{
		if( $url != '' )
		{
			WxPayConfig::$notifyUrl = $url;
		}
	}
	/**
	 * 设置同步返回地址
	 * @param 参数1，同步返回地址
	 */
	function SetReturnUrl($url='')
	{
		if( $url != '' )
		{
			WxPayConfig::$returnUrl = $url;
		}
	}
	

	/**
	 * 下单方法
	 * @param 参数1，必须，订单数据
	 */
	function Order($orderData)
	{
		//如果是微信js_api支付
		if( defined('WXPAY_JSAPI') )
		{
			return $this->JsApiOrder($orderData);
		}
		else if( defined('WXPAY_APP') )
		{
			return $this->AppOrder($orderData);
		}
		else
		{
			return $this->NativeOrder($orderData);
		}
	}
	//扫码支付
	function NativeOrder($orderData)
	{
		//引入扫码支付方法
		require_once "lib/WxPay.NativePay.php";
		//下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($orderData['body']);
		$input->SetOut_trade_no($orderData['sn']);
		$input->SetTotal_fee($orderData['money']*100);
		$input->SetNotify_url(WxPayConfig::$notifyUrl);
		$input->SetProduct_id($orderData['sn']);
		$input->SetTrade_type("NATIVE");
		//设置附加信息。
		$params = array();
		if( isset($orderData['params']) )
		{
			$params = $orderData['params'];
		}
		$params['pay_type'] = 'wxpay';
		$input->SetAttach(UrlEncode(json_encode($params)));
		//返回二维码的地址
		$notify = new NativePay();
		$result = $notify->GetPayUrl($input);
		return $result["code_url"];
	}
	//jsapi支付。
	function JsApiOrder($orderData)
	{
		//引入JSAPI支付方法
		require_once "lib/WxPay.JsApiPay.php";
		//①、获取用户openid
		$tools = new JsApiPay();
			
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($orderData['body']);
		$input->SetOut_trade_no($orderData['sn']);
		$input->SetTotal_fee($orderData['money']*100);
		$input->SetNotify_url(WxPayConfig::$notifyUrl);
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($this->openId);
		//设置附加信息。
		$params = GetKey($orderData,'params');
		$params['pay_type'] = 'wxpay_jsapi';
		$input->SetAttach(UrlEncode(json_encode($params)));
		$order = WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		return $jsApiParameters;
	}
	//APP支付
	function AppOrder($orderData)
	{
		//引入APP支付方法
		require_once "lib/WxPay.AppPay.php";
		//下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($orderData['body']);
		$input->SetOut_trade_no($orderData['sn']);
		$input->SetTotal_fee($orderData['money']*100);
		$input->SetNotify_url(WxPayConfig::$notifyUrl);
		$input->SetProduct_id($orderData['sn']);
		$input->SetTrade_type("APP");
		//设置附加信息。
		$params = array();
		if( isset($orderData['params']) )
		{
			$params = $orderData['params'];
		}
		$params['pay_type'] = 'wxpay_app';
		$input->SetAttach(UrlEncode(json_encode($params)));
		$appPay = new AppPay();
		$result = $appPay->GetParameters(WxPayApi::unifiedOrder($input));
		return $result;
	}
	

	/**
	 * 异步通知方法
	 */
	function Notify()
	{
		//引入异步通知方法
		require_once "lib/WxPay.Notify.php";
		$inputData = file_get_contents('php://input');

		$notify = new WxPayNotify();
		$result = $notify->Handle(true,true);

		if($result['return_code']=='SUCCESS')
		{
			$this->logHandler->setHandle('success');
			Log::DEBUG($inputData);
		
			//将XML转为array
			//禁止引用外部xml实体
			libxml_disable_entity_loader(true);
			$inputData = json_decode(json_encode(simplexml_load_string($inputData, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			$data['out_trade_no'] = $inputData['out_trade_no'];
			$data['trade_no'] = $inputData['transaction_id'];
			return $data;
		}
		else
		{
			$this->logHandler->setHandle('fail');
			Log::DEBUG($inputData);
			return false;
		}
	}
}
?>