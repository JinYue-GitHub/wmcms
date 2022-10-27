<?php
require_once("SmsConfig.php");
require_once("SmsClient.php");

//wmcms整合短信类
class Sms{
	private $client;
	// fixme 必填：是否启用https
	private $security = false;
	
	function __construct($config)
	{
		$this->client = new SmsClient($config);
	}
	
	
	/**
	 * 发送短信
	 */
	function SendSms($data)
	{
		$params = array();
		// 必填: 短信接收号码
		$params["PhoneNumbers"] = $data["receive"];
		// 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
		$params["SignName"] = $data["sign"];
		// 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
		$params["TemplateCode"] = $data["tempCode"];
		// 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
		$params['TemplateParam'] = $data['params'];
		// 可选: 设置发送短信流水号
		//$params['OutId'] = "12345";
		// 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
		//$params['SmsUpExtendCode'] = "1234567";
		
		//发送接口配置
		$params['RegionId'] = "cn-hangzhou";
		$params['Action'] = "SendSms";
		$params['Version'] = "2017-05-25";
		// *** 以下代码若无必要无需更改 ***
		if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
			$params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
		}
		$result = $this->client->request($this->client->sendSmsDomain,$params);
		if( isset($result['errmsg']) )
		{
		    return array('code'=>500,'msg'=>$result['errmsg']);
		}
		return array('code'=>200,'msg'=>'发送成功');
	}

	/**
	 * 批量发送短信
	 */
	function SendBatchSms()
	{
		$params = array();
		// 必填: 待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
		$params["PhoneNumberJson"] = array("1500000000","1500000001",);
		// fixme 必填: 短信签名，支持不同的号码发送不同的短信签名，每个签名都应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
		$params["SignNameJson"] = array("云通信","云通信2",);
		// 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
		$params["TemplateCode"] = "SMS_1000000";
		// 必填: 模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
		// 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
		$params["TemplateParamJson"] = array(
			array("name" => "Tom","code" => "123",),
			array("name" => "Jack","code" => "456",),
		);
		// 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
		// $params["SmsUpExtendCodeJson"] = json_encode(array("90997","90998"));

		//发送接口配置
		$params['RegionId'] = "cn-hangzhou";
		$params['Action'] = "SendBatchSms";
		$params['Version'] = "2017-05-25";
		// *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
		$params["TemplateParamJson"]  = json_encode($params["TemplateParamJson"], JSON_UNESCAPED_UNICODE);
		$params["SignNameJson"] = json_encode($params["SignNameJson"], JSON_UNESCAPED_UNICODE);
		$params["PhoneNumberJson"] = json_encode($params["PhoneNumberJson"], JSON_UNESCAPED_UNICODE);
		if(!empty($params["SmsUpExtendCodeJson"]) && is_array($params["SmsUpExtendCodeJson"])) {
			$params["SmsUpExtendCodeJson"] = json_encode($params["SmsUpExtendCodeJson"], JSON_UNESCAPED_UNICODE);
		}
		return false;
		//return $this->client->request($this->client->sendSmsDomain,$params);
	}



	/**
	 * 短信发送记录查询
	 */
	function QuerySendDetails()
	{
		$params = array ();
		// 必填: 短信接收号码
		$params["PhoneNumber"] = "17000000000";
		// 必填: 短信发送日期，格式Ymd，支持近30天记录查询
		$params["SendDate"] = "20170710";
		// 必填: 分页大小
		$params["PageSize"] = 10;
		// 必填: 当前页码
		$params["CurrentPage"] = 1;
		// 可选: 设置发送短信流水号
		//$params["BizId"] = "yourBizId";
		//发送接口配置
		$params['RegionId'] = "cn-hangzhou";
		$params['Action'] = "QuerySendDetails";
		$params['Version'] = "2017-05-25";
		return false;
		//$this->client->request($this->client->sendSmsDomain,$params);
	}

}