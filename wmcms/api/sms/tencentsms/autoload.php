<?php
//wmcms整合短信类
class Sms{
	//短信应用的APPID
    private $appId;
	// 密钥参数，云API密匙查询: https://console.cloud.tencent.com/cam/capi
    private $secretId;
    private $secretKey;
	//短信发送域名
	private $sendSmsDomain = "sms.tencentcloudapi.com";
	//短信地域
	private $region = 'ap-guangzhou';
	//短信接口版本
	private $version = '2021-01-11';
	//短信接口方法
	private $action = 'SendSms';
	private $sessionContext = 'WMCMS';
	
	public function __construct($config=array())
	{
		$this->appId = C('config.api.tencentsms.api_appid');
		$this->secretId = C('config.api.tencentsms.api_apikey');
		$this->secretKey = C('config.api.tencentsms.api_secretkey');
	}
	
	//获得签名
	private function Sign($param)
	{
		ksort($param);
		$signStr = "GET" . $this->sendSmsDomain . "/?";
		foreach ( $param as $key => $value ) {
			$signStr = $signStr . $key . "=" . $value . "&";
		}
		$signStr = substr($signStr, 0, -1);
		$signature = base64_encode(hash_hmac("sha1", $signStr, $this->secretKey, true));
		$param["Signature"] = $signature;
		$paramStr = "";
		foreach ( $param as $key => $value ) {
			$paramStr = $paramStr . $key . "=" . urlencode($value) . "&";
		}
		return substr($paramStr, 0, -1);
	}
	/**
	 * 发送短信
	 */
	function SendSms($data)
	{
		// 实际调用需要更新参数，这里仅作为演示签名验证通过的例子
		$param = array(
			"Nonce" => rand(10000,99999),
			"Timestamp" => time(),
			"Region" => $this->region,
			"SecretId" => $this->secretId,
			"Version" => $this->version,
			"Action" => $this->action,
			"SmsSdkAppId" => $this->appId,
			"SessionContext" => $this->sessionContext,
			"SignName" => $data["sign"],
			"TemplateId" => $data["tempCode"],
			"PhoneNumberSet.0" => $data["receive"],
		);
		//附加参数
		foreach ($data['params'] as $k=>$v)
		{
		    $param['TemplateParamSet.'.($k-1)] = $v;
		}
		//请求接口
		$url = "https://" . $this->sendSmsDomain . "/?". $this->Sign($param);
		$httpSer = NewClass('http');
		$result = json_decode($httpSer->GetUrl($url),true);
		if( isset($result['Response']['Error']['Message']) )
		{
			$result = array('code'=>500,'msg'=>$result['Response']['Error']['Message']);
		}
		else if( strtolower($result['Response']['SendStatusSet'][0]['Code']) != "ok" )
		{
			$result = array('code'=>500,'msg'=>$result['Response']['SendStatusSet'][0]['Message']);
		}
		else
		{
			$result = array('code'=>200,'msg'=>'发送成功');
		}
		return $result;
	}

	/**
	 * 批量发送短信
	 */
	function SendBatchSms()
	{
		return false;
	}


	/**
	 * 短信发送记录查询
	 */
	function QuerySendDetails()
	{
		return false;
	}

}