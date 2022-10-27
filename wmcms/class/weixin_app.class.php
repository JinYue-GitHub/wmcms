<?php
use Qiniu\json_decode;
/**
* 微信小程序类
*
* @version        $Id: weixin_app.class.php 2018年9月11日 20:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
require_once 'weixin.class.php';

class WeiXin_App extends WeiXin{
	
	function __construct($data = array())
	{
		parent::__construct($data);
	}
	
	
	/**
	 * 获得用户openid
	 * @param 参数1，必填，交换sessionkey的jscode
	 */
	function GetSessionKey($jsCode)
	{
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->appid.'&secret='.$this->secret.'&js_code='.$jsCode.'&grant_type=authorization_code';
		$data = $this->__GetUrl($url);
		
		return $data;
	}

	
	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param 参数1，必须， string 加密后的用户数据
	 * @param 参数2，必须，登录时获得的sessionkey
	 * @param 参数3，必须，与用户数据一同返回的初始向量
	 */
	public function DecryptData( $encryptedData,$key, $iv)
	{
		if (strlen($key) != 24)
		{
			return array('errmsg'=>'sessionkey is error');
		}
		$aesKey=base64_decode($key);
	
	
		if (strlen($iv) != 24) {
			return array('errmsg'=>'iv is error');
		}
		$aesIV = base64_decode($iv);
	
		$aesCipher = base64_decode($encryptedData);
	
		$result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
	
		$dataObj = json_decode( $result );
		if( $dataObj  == NULL )
		{
			return array('errmsg'=>'buffer is error');
		}
		
		if( $dataObj->watermark->appid != $this->appid )
		{
			return array('errmsg'=>'appid is error');
		}

		return json_decode($result,true);
	}
}
?>