<?php
require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");

$target_service = "user.auth.quick.login";
$anti_phishing_key = "";
$exter_invoke_ip = "";

$parameter = array(
	"service" => "alipay.auth.authorize",
	"partner" => trim($alipay_config['partner']),
	"target_service"	=> $target_service,
	"return_url"	=> $backurl,
	"anti_phishing_key"	=> $anti_phishing_key,
	"exter_invoke_ip"	=> $exter_invoke_ip,
	"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);


//wmcms整合登录类
class OtherLogin{
	private $config;
	private $parStr;
	function __construct($data=array())
	{
		global $alipay_config;
		global $parameter;
		$this->config = $alipay_config;
		$this->parStr = $parameter;
	}

	//获得跳转登录的url，qq登录是直接跳转。
	function GetJumpUrl()
	{
		$alipaySubmit = new AlipaySubmit($this->config);
		$loginurl = $alipaySubmit->alipay_gateway_new.$alipaySubmit->buildRequestParaToString($this->parStr);
		return $loginurl;
	}
	
	//获得用户的信息
	function GetUserInfo($token='',$openid='')
	{
		$alipayNotify = new AlipayNotify($this->config);
		$verify_result = $alipayNotify->verifyReturn();
		//验证成功
		if($verify_result)
		{
			$data['type'] = '支付宝';
			$data['openid'] = $_GET['user_id'];
			$data['nickname'] = $_GET['real_name'];
			$data['unionid'] = isset($_GET['unionid'])?$_GET['unionid']:'';
			return $data;
		}
		else
		{
			tpl::ErrInfo('验证失败！');
		}
	}
}
