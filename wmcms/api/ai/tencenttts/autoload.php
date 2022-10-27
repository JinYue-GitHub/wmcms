<?php
class Ai
{
	// -------------- Required. 请登录腾讯云官网控制台获取 ---------------------
	private $secretId = "Your SecretId";
	private $secretKey = "Your SecretKey";
	private $APPID = 0;
	private $VERSION = '';
	// --------------- 必需，不可更改 ---------------------
	/* 请求参数设置,无需改变 */
	private $ACTION = 'TextToStreamAudio';
	// 默认编码, php只支持pcm格式
	private $CODEC = 'mp3';
	//模型类型，1：默认模型
	private $MODEL_TYPE = 1;
	// --------------- Optional, 默认无需更改 ----------------
	/* 音频采样率： 16000：16k（默认）  8000：8k */
	private $SAMPLE_RATE = 16000;
	# 项目 ID，用户自定义，默认为0
	private $PROJECT_ID = 0;
	// 语言 1 ： CN ， 2 ： EN
	private $PRIMARY_LANGUAGE = 1;
	//请求鉴权的有效时间，单位 s，默认1h
	private $EXPIRED = 3600;
	// --------------- Optional, 请按需修改 ----------------
	/*音色：参考 https://cloud.tencent.com/document/product/1073/34093 */
	private $VOICET_TYPE = 10510000;
	//文本内容
	private $TEXT = '你好，WMCMS';
	/* 语速，范围：[-2，2]，分别对应不同语速： -2代表0.6倍 -1代表0.8倍 0代表1.0倍（默认） 1代表1.2倍 2代表1.5倍	若需要更细化的语速档次，可以保留小数点一位，如-1.1 0.5 1.7等。*/
	private $SPEED = 0;
	private $speedArr = array('-2'=>-1,'-1'=>-0.6,'0'=>0,'1'=>0.6,'2'=>1);
	// 音量大小，范围：[0，10]，分别对应11个等级的音量，默认值为0，代表正常音量。没有静音选项
	private $VOLUME = 0;
	
	public function __construct($config=array())
	{
		$this->APPID = intval(C('config.api.tencenttts.api_appid'));
		$this->secretId = C('config.api.tencenttts.api_apikey');
		$this->secretKey = C('config.api.tencenttts.api_secretkey');
	}
	
    /**
     * 设置默认参数
     * data 需要设置的参数
     */
	private function SetParams($data)
	{
        $this->TEXT = $data['text'];
        $this->SPEED = 0;
        if( isset($this->speedArr[$data['speed']]) )
        {
            $this->SPEED = $this->speedArr[$data['speed']];
        }
        if( isset($data['voicet']) )
        {
            $this->VOICET_TYPE = intval($data['voicet']);
        }
        if( isset($data['volume']) )
        {
            $this->VOLUME = intval($data['volume']);
        }
		$reqArr =  array();
    	$reqArr['Codec'] = $this->CODEC;
    	$reqArr['ModelType'] = $this->MODEL_TYPE;
    	$reqArr['PrimaryLanguage'] = $this->PRIMARY_LANGUAGE;
    	$reqArr['ProjectId'] = $this->PROJECT_ID;
    	$reqArr['SampleRate'] = $this->SAMPLE_RATE;
    	$reqArr['VoiceType'] = $this->VOICET_TYPE;
    	$reqArr['Volume'] = $this->VOLUME;
    	$reqArr['Timestamp'] = time();
    	$reqArr['Speed'] = $this->SPEED;
    	$reqArr['Text'] = $this->TEXT;
    	$reqArr['SecretId'] = $this->secretId;
		return $reqArr;
	}
	
    /**
     * 获取签名鉴权
     * reqArr 请求原始数据
     * method 请求方式 POST
     * domain 请求域名
     * path 请求路径
     * secretKey 用户秘钥
     * output str 鉴权签名signature
     */
    private function CreateSign($reqArr, $method, $domain, $path, $secretKey)
    {
    	$signStr = "";
    	$signStr .= $method;
    	$signStr .= $domain;
    	$signStr .= $path;
    	$signStr .= "?";
    	ksort($reqArr, SORT_STRING);
    	foreach ($reqArr as $key => $val) {
    		$signStr .= $key . "=" . $val . "&";
    	}
    	$signStr = substr($signStr, 0, -1);
    	$signStr = base64_encode(hash_hmac('SHA1', $signStr, $secretKey, true));
    	return $signStr;
    }
    
    /**
     * http post请求
     * url 请求链接地址
     * data 请求数据
     * rsp_str  回包数据
     * http_code 请求状态码
     * method 请求方式，默认POST
     * header http请求头
     * output int 请求结果
     */
    function GetUrl($url, $data, $headers = array (), $method = 'POST')
    {
    	$ch = curl_init();
    	//不是get请求
    	if ( $method != 'GET' )
    	{
    	    if( is_array($data) )
    	    {
    		    $data = json_encode($data);
    	    }
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	}
    	//get请求封装url参数
    	else
    	{
    	    if( is_array($data) )
    	    {
    		    $data = http_build_query($data);
    	    }
    	    
    		if (strpos($url, '?') === false)
    		{
    			$url .= '?';
    		}
    		else
    		{
    			$url .= '&';
    		}
    		$url .= $data;
    	}
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	$rsp_str = curl_exec($ch);
    	curl_close($ch);
    	
    	$result = json_decode($rsp_str,true);
    	if ( is_array($result) && isset($result['Response']['Error']['Message']) )
    	{
    	    return array('code'=>500,'msg'=>$result['Response']['Error']['Message']);
    	}
    	else
    	{
    	    return array('code'=>200,'data'=>$rsp_str);
    	}
    }
    
    /**
     * 短文本请求获取语音
     * output str 音频mp3格式
     */
    function Synthesis($data,$request=true)
    {
        $this->SetParams($data);
    	$reqArr = $this->SetParams($data);
		$reqArr['Action'] = 'TextToStreamAudio';
		$reqArr['Action'] = 'TextToVoice';
		$reqArr['Version'] = '2019-08-23';
    	$reqArr['AppId'] = $this->APPID;
    	$reqArr['Expired'] = $this->EXPIRED + time();//表示为离线识别
    	$reqArr['SessionId'] = md5(time());
    	$serverUrl = "https://tts.cloud.tencent.com/stream";
    	$autho = $this->createSign($reqArr, "POST", "tts.cloud.tencent.com", "/stream", $this->secretKey);
    	$header = array (
    		'Authorization: ' . $autho,
    		'Content-Type: ' . 'application/json',
    	);
    	
		if( $request == false )
		{
			return array('url'=>$serverUrl,'data'=>json_encode($reqArr),'header'=>$header);
		}
		else
		{
			$result = $this->GetUrl($serverUrl, json_encode($reqArr),$header);
			if ( !$result )
			{
				return array('code'=>500,'msg'=>'GetUrl failed');
			}
			else if( $result['code'] == '200' )
			{
				$result['data'] = base64_decode(json_decode($result['data'],true)['Response']['Audio']);
			}
			return $result;
		}
    }
    
    //长文本合成
    function LangTextSynthesis($data)
    {
        $reqArr = $this->SetParams($data);
		$reqArr['Nonce'] = rand(10000,99999);
		$reqArr['Action'] = 'CreateTtsTask';
		$reqArr['Version'] = '2019-08-23';
    	$serverUrl = "https://tts.tencentcloudapi.com";
    	$reqArr['Signature'] = $this->CreateSign($reqArr, "POST", "tts.tencentcloudapi.com", "/", $this->secretKey);
    	$result = $this->GetUrl($serverUrl, http_build_query($reqArr));
    	if ( !$result )
    	{
    	    return array('code'=>500,'msg'=>'GetUrl failed');
    	}
    	return $result;
    }
}
?>