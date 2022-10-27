<?php
/**
 * 阿里云短信配置
 * Class SmsConfig
 */
class SmsConfig {
	//accessKeyId 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    protected $accessKeyId;
	//accessKeySecret
    protected $accessKeySecret;
	//是否启用https
	protected $security = false;
	//短信发送域名
	public $sendSmsDomain = "dysmsapi.aliyuncs.com";
	
	public function __construct($config)
	{
		if( isset($config['api_apikey']) )
		{
			$this->accessKeyId = $config['api_apikey'];
		}
		if( isset($config['api_secretkey']) )
		{
			$this->accessKeySecret = $config['api_secretkey'];
		}
	}
}