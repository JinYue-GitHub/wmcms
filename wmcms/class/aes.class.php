<?php
/**
* AES非对称加密算法
*
* @version        $Id: aes.class.php 2016年月19日 13:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class aes{
	//加密模式  cbc 十六进制
	//key
    private $key = "WMCMS_SDKJ@$";
    //偏移量
    private $iv = "5efd3f6060e20330";
 
	//构造函数
	function __construct()
	{
		//网站的api通信key
		$apikey = C('config.api.system.api_apikey');
		//域名
		$domain = $_SERVER['HTTP_HOST'];
		//设置偏移量
		$this->iv = substr(md5($apikey), 0, 16);
		//设置加密key
		$this->key = 'WMCMS_'.substr(md5($apikey.$domain), 0, 16);
	}
	
    //pkcs7补码
    private function addPkcs7Padding($string, $blocksize = 32)
    {
        $len = strlen($string); //取得字符串长度
        $pad = $blocksize - ($len % $blocksize); //取得补码的长度
        $string .= str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
        return $string;
    }

	//除去pkcs7
    private function stripPkcs7Padding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);

        if(preg_match("/$slastc{".$slast."}/", $string)){
            $string = substr($string, 0, strlen($string)-$slast);
            return $string;
        } else {
            return false;
        }
    }
  

    //十六进制转字符串
    private function hexToStr($hex)
    {   
        $string=""; 
        for($i=0;$i<strlen($hex)-1;$i+=2)
        $string.=chr(hexdec($hex[$i].$hex[$i+1]));
        return  $string;
    }
    //字符串转十六进制
    private function strToHex($string)
    { 
        $hex="";
        $tmp="";
        for($i=0;$i<strlen($string);$i++)
        {
            $tmp = dechex(ord($string[$i]));
            $hex.= strlen($tmp) == 1 ? "0".$tmp : $tmp;
        }
        $hex=strtoupper($hex);
        return $hex;
    }
    
    /**
     * 解密
     * @param 需要解密的密文
     */
    function decrypt($encryptedText)
    {
        $str = $this->hexToStr($encryptedText);
        $str = $this->stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $str, MCRYPT_MODE_CBC, $this->iv));
        //如果进行json格式转换后的数据为空就表明是字符串，就直接返回。
        if ( is_null(json_decode($str)) )
        {
        	return $str;
        }
        else
        {
        	return json_decode($str,true);
        }
    }

    /**
     * 对字符串或者数组进行aes加密
     * @param 需要进行加密的字符串或者数组
     */
    function encrypt($str)
    {
    	//如果是数组就进行json转换
    	if( is_array($str) )
    	{
    		$str = json_encode($str);
    	}
        $base = (mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key,$this->addPkcs7Padding($str,16) , MCRYPT_MODE_CBC, $this->iv));
        return $this->strToHex($base);
    }
}
?>