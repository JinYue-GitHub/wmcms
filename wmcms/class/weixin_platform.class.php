<?php
/**
* 微信公众号平台类
*
* @version        $Id: weixin_platform.class.php 2019年03月09日 19:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
require_once 'weixin.class.php';

class WeiXin_Platform extends WeiXin{
	public $data;
	//token的保存路径
	public $tokenFile;
	//ticket的保存路径
	public $ticketFile;
	//log文件的保存路径
	public $logPath;
	//消息对象数组
	public $responseMsg = array();
	
	function __construct($data = array())
	{
		parent::__construct($data);
		$this->tokenFile = WMCACHE.'platform/weixin/access_token_'.GetEStr().'.php';
		$this->ticketFile = WMCACHE.'platform/weixin/ticket_'.GetEStr().'.php';
		$this->logPath = WMCACHE.'log/platform/weixin/';
		if( !file_exists($this->tokenFile) )
		{
			file::CreateFile($this->tokenFile, '<?php die();/**/?>',1);
		}
		if( !file_exists($this->ticketFile) )
		{
			file::CreateFile($this->ticketFile, '<?php die();/**/?>',1);
		}
	}
	
	/**
	 * 获得平台的token，和小程序公众号临时的不一样，有上限access_token
	 */
	function GetToken()
	{
		$data = json_decode(substr(file_get_contents($this->tokenFile), 14, -5));
		//判断是否过期
		if ( !$data || $data->expire_time < time() )
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
			$res = $this->__GetUrl($url);
			if ( isset($res['access_token']) )
			{
				//增加过期时间
				$res['expire_time'] = time() + 7100;
				//为了安全，保存为php文件
				file::CreateFile($this->tokenFile, '<?php die();/*'.json_encode($res).';*/?>',1);
				$access_token = $res['access_token'];
			}
			else
			{
				die(json_encode($res));
			}
		}
		else
		{
			$access_token = $data->access_token;
		}
		return $access_token;
	}
	
	/**
	 * 获得jssdk的Ticket
	 */
	private function GetJsApiTicket()
    {
        //jsapi_ticket 应该全局存储与更新
        $data = json_decode(substr(file_get_contents($this->ticketFile), 14, -5));
        if ( empty($data) ||  $data->expire_time < time())
        {
            $accessToken = $this->GetToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$accessToken."&type=jsapi";
            $res = json_decode($this->__GetUrl($url));
            if ( isset($res['ticket']) )
            {
                //增加过期时间
                $res['expire_time'] = time() + 7100;
                //为了安全，保存为php文件
                file::CreateFile($this->ticketFile, '<?php die();/*'.json_encode($res).';*/?>',1);
                $ticket = $res['ticket'];
            }
        }
        else
        {
			$ticket = $data->ticket;
        }
        return $ticket;
    }
    
    /**
     * 获得jsapi的加密字符串接口
     * @param 参数1，长度
     */
    private function GetJsApiNonceStr($length = 16)
    {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    	$str = "";
    	for ($i = 0; $i < $length; $i++) {
    		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    	}
    	return $str;
    }
	
	/**
	 * 获得jssdk的加密签名包
	 * @return multitype:unknown string NULL number
	 */
	public function GetJSSDKSignPackage()
	{
		//当前url
		$url = HTTP_TYPE.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		//获得jssdk的ticket
		$jsapiTicket = $this->GetJsApiTicket();
		//时间戳
		$timestamp = time();
		//获得jssdk的签名字符串
		$nonceStr = $this->GetJsApiNonceStr();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = [
			"appId" => $this->appid,
			"nonceStr" => $nonceStr,
			"timestamp" => $timestamp,
			"url" => $url,
			"signature" => $signature,
			"rawString" => $string
		];
		return $signPackage;
	}
	
	//微信公众号请求正确性验证
	function CheckSignature($token)
	{
		$signature  =  Get("signature");
		$timestamp  =  Get("timestamp");
		$nonce      =  Get("nonce");
		$tmpArr     =  array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * 获得用户信息
	 * @param 参数1，必须，用户的openid
	 */
	function UserGetInfo($openid,$lang='zh_CN')
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->GetToken().'&openid='.$openid.'&lang='.$lang;
		return $this->__GetUrl($url);
	}
	
	
	/**
	 * 公众号当前菜单创建
	 * @param 参数1，必须，菜单数据
	 * @return mixed
	 */
	function MenuCreate($data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->GetToken();
		return $this->__GetUrl($url,$data);
	}
	//公众号当前菜单获取
	function MenuGet()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->GetToken();
		return $this->__GetUrl($url);
	}
	//公众号当前菜单删除
	function MenuDel()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->GetToken();
		return $this->__GetUrl($url);
	}
	
	
	/**
	 * 上传永久素材
	 * @param 参数1，必须，是否是永久素材
	 * @param 参数2，必须，素材类型
	 * @param 参数3，必须，素材路径
	 */
	function MediaAdd($isLong,$type,$path)
	{
		$data = array( 'media' => new CURLFile(realpath(WMROOT.$path)) );
		//永久上传url
		if( $isLong == '1' )
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->GetToken().'&type='.$type;
		}
		//临时上传url
		else
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->GetToken().'&type='.$type;
		}
		return $this->__GetUrl($url,$data);
	}
	
	
	
	/**
	 * 消息模版获
	 * @param 参数1，必须，模版类型
	 * @return fun(...$pars)可使用无限参数方法，php5.6+，这里使用兼容性广的func_get_args
	 */
	function ResponseGetTemp($type)
	{
		$args = func_get_args();
		//基础模版封装
		$template = "<ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType>";
		//内容封装
		switch ($type)
		{
			case 'text':
			case 'tag':
				if( empty($args[1]) )
				{
					return '';
				}
				$template .= "<![CDATA[text]]></MsgType><Content><![CDATA[{$args[1]}]]></Content>";
				break;
			case 'image':
				$template .= "<![CDATA[{$type}]]></MsgType><Image><MediaId><![CDATA[{$args[1]}]]></MediaId></Image>";
				break;
		}
		
		$template = "<xml>{$template}</xml>";
		return $template;
	}
	
	/**
	 * 获得关键词匹配的消息
	 * @param 参数1，必须，查询的关键字
	 */
	function ResponseGetMatch($key)
	{
		$replyMod = NewModel('operate.weixin_autoreply');
		$matchData = $replyMod->GetKeyMatchOne($key);
		//默认基础数据
		$toUser   =  $this->responseMsg['data']->FromUserName;
		$fromUser =  $this->responseMsg['data']->ToUserName;
		$time     =  time();
		//在发送的消息中匹配到了关键字
		if( $matchData )
		{
			$template = str::EnCoding($matchData['autoreply_temp']);
			//如果是标签消息
			if( $matchData['autoreply_type'] == 'tag' )
			{
    			$tagLabel = tpl::Tag('<Content><![CDATA[[a]]]></Content>',$template);
    			if( !empty($tagLabel[1][0]) )
    			{
                    //设置页面信息
                    C('page',array('pagetype'=>'index','tempid'=>'string','tpath'=>$tagLabel[1][0]));
                    //new一个模版类，然后输出
                    $tpl=new tpl();
                    $content = $tpl->display('return');
                    $template = tpl::Rep(array($tagLabel[1][0]=>$content),$template,'2');
    			}
			}
			return sprintf($template, $toUser, $fromUser, $time);
		}
		else
		{
			//没有消息回复
			if( empty($this->responseMsg['welcome_temp']) )
			{
				return 'success';
			}
			else
			{
				$template = str::EnCoding($this->responseMsg['default_temp']);
				return sprintf($template, $toUser, $fromUser, $time);
			}
		}
	}
	
	//消息处理事件
	function ResponseMsg()
	{
    	$postArr  =  file_get_contents("php://input");
	    $postObj  = simplexml_load_string($postArr, 'SimpleXMLElement', LIBXML_NOCDATA);
	    if( !$postObj )
	    {
	    	return false;
	    }
	    else
	    {
	    	$this->responseMsg['data'] = $postObj;
	    	$this->responseMsg['data']['attr'] = '';
	    	//默认基础数据
		    $toUser   =  $postObj->FromUserName;
		    $fromUser =  $postObj->ToUserName;
		    $time     =  time();
			//判断该数据包是否是订阅事件类型
		    if (strtolower($postObj->MsgType) == 'event')
		    {
		    	//如果是subscribe 关注事件场景
		        if ( strtolower($postObj->Event) == 'subscribe')
		        {
		    		//没有消息回复
		        	if( empty($this->responseMsg['welcome_temp']) )
		        	{
		        		return 'success';
		        	}
		        	else
		        	{
						$template = str::EnCoding($this->responseMsg['welcome_temp']);
			    		return sprintf($template, $toUser, $fromUser, $time);
		        	}
				}
		        //取消关注unsubscribe 取消关注事件
		        else if ( strtolower($postObj->Event) == 'unsubscribe')
		        {
		        	return 'success';
		        }
		        //点击菜单事件
		        else if ( strtolower($postObj->Event) == 'click')
		        {
		        	return $this->ResponseGetMatch($postObj->EventKey);
		        }
		        else
		        {
		        	return 'success';
		        }
		    }
		    //普通文字消息
		    else if (strtolower($postObj->MsgType) == 'text')
		    {
		    	return $this->ResponseGetMatch($postObj->Content);
		    }
		    //普通语音消息
		    else if (strtolower($postObj->MsgType) == 'voice')
		    {
		    	$this->responseMsg['data']['attr'] = '{"Format":"'.$postObj->Format.'"}';
		    }
		    //普通视频消息
		    else if (strtolower($postObj->MsgType) == 'video')
		    {
		    	$this->responseMsg['data']['attr'] = '{"ThumbMediaId":"'.$postObj->ThumbMediaId.'"}';
		    }
		    //普通短视频消息
		    else if (strtolower($postObj->MsgType) == 'shortvideo')
		    {
		    	$this->responseMsg['data']['attr'] = '{"ThumbMediaId":"'.$postObj->ThumbMediaId.'"}';
		    }
		    //普通地理位置消息
		    else if (strtolower($postObj->MsgType) == 'location')
		    {
		    	$this->responseMsg['data']['attr'] = '{"Location_X":"'.$postObj->Location_X.'","Location_Y":"'.$postObj->Location_Y.'","Scale":"'.$postObj->Scale.'","Label":"'.$postObj->Label.'"}';
		    }
		    //普通超链接消息
		    else if (strtolower($postObj->MsgType) == 'link')
		    {
		    	$this->responseMsg['data']['attr'] = '{"Title":"'.$postObj->Title.'","Description":"'.$postObj->Description.'"}';
		    }
		    
		    return 'success';
	    }
	}
}
?>