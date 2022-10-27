<?php
class Ai
{
	// -------------- Required. 请登录百度创建应用 ---------------------
	private $apiKey = "Your SecretId";
	private $secretKey = "Your SecretKey";
	private $appid = 0;
	// --------------- 必需，不可更改 ---------------------
	// 文件格式, 3：mp3(default) 4： pcm-16k 5： pcm-8k 6. wav
	private $aue = 3;
	// --------------- Optional, 请按需修改 ----------------
	/*音色：发音人，请前往百度开通
	# 发音人选择, 基础音库：0为度小美，1为度小宇，3为度逍遥，4为度丫丫，
    # 精品音库：5为度小娇，103为度米朵，106为度博文，110为度小童，111为度小萌，默认为度小美 
	*/
	private $per = 0;
	/* 语速，语速，取值0-15，默认为5中语速*/
	private $spd = 5;
	private $speedArr = array('-2'=>3,'-1'=>4,'0'=>5,'1'=>6,'2'=>7);
	// 音调，取值0-15，默认为5中语调
	private $pit = 5;
	// 音量，取值0-9，默认为5中音量
	private $vol = 5;
	//文本内容
	private $text = '你好，WMCMS';

	public function __construct($config=array())
	{
		$this->appid = intval(C('config.api.baidutts.api_appid'));
		$this->apiKey = C('config.api.baidutts.api_apikey');
		$this->secretKey = C('config.api.baidutts.api_secretkey');
	}
	
    /**
     * 获取请求token
     */
    function GetToken()
    {
        $auth_url = "https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=".$this->apiKey."&client_secret=".$this->secretKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //信任任何证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 检查证书中是否设置域名,0不验证
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        $res = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($res, true);
        if( !isset($response['access_token']) )
        {
            return array('code'=>500,'msg'=>'ERROR TO OBTAIN TOKEN');
        }
        else if( !isset($response['scope']) )
        {
            return array('code'=>500,'msg'=>'ERROR TO OBTAIN scopes');
        }
        else if( !in_array('audio_tts_post',explode(" ", $response['scope'])) )
        {
            return array('code'=>500,'msg'=>'请至网页上应用内开通语音合成权限');
        }
        else
        {
            return array('code'=>200,'data'=>$response['access_token']);
        }
    }
    
    /**
     * 请求获取语音
     */
    function Synthesis($data)
    {
        $this->text = $data['text'];
        $this->spd = 5;
        if( isset($this->speedArr[$data['speed']]) )
        {
            $this->spd = $this->speedArr[$data['speed']];
        }
        if( isset($data['voicet']) )
        {
            $this->per = intval($data['voicet']);
        }
        if( isset($data['volume']) )
        {
            $this->vol = $data['volume'];
        }
        //获取token
        $result = $this->GetToken();
        if( $result['code']!='200' )
        {
            return $result;
        }
        $token = $result['data'];
        //拼装请求参数
        $params = array(
             // 为避免+等特殊字符没有编码，此处需要2次urlencode。
        	'tex' => urlencode($this->text),
        	'tok' => $token,
        	'cuid' => md5(time().rand(10000,99999)),
        	'ctp' => 1, // 固定参数
        	'lan' => 'zh', //固定参数
        	'spd' => $this->spd,
        	'pit' => $this->pit,
        	'vol' => $this->vol,
        	'per' => $this->per,
        	'aue' => $this->aue,
        );
        $paramsStr =  http_build_query($params);
        $url = 'http://tsn.baidu.com/text2audio';
        //请求接口
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsStr);
        $data = curl_exec($ch);
        curl_close($ch);
    	$result = json_decode($data,true);
    	if( is_array($result) )
    	{
    	    return array('code'=>500,'msg'=>$result['err_msg']);
    	}
    	else
    	{
    	    return array('code'=>200,'data'=>$data);
    	}
    }
}
?>