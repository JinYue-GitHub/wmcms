<?php
/**
 * 
 * APP支付实现类
 * @author widy
 *
 */
class AppPay
{
	public $data = null;
	
	
	/**
	 * 
	 * 获取APP支付的参数
	 * @param array $UnifiedOrderResult 统一支付接口返回的数据
	 * @throws WxPayException
	 * 
	 * @return array数据，直接返回
	 */
	public function GetParameters($UnifiedOrderResult)
	{
		if( isset($UnifiedOrderResult['return_code']) && $UnifiedOrderResult['return_code'] =='FAIL' )
		{
			throw new WxPayException($UnifiedOrderResult['return_msg']);
		}
		else if(!array_key_exists("appid", $UnifiedOrderResult) )
		{
			throw new WxPayException("参数错误");
		}
		$appApi = new WxPayAppPay();
		$appApi->SetAppid($UnifiedOrderResult["appid"]);
		$appApi->SetPartnerId($UnifiedOrderResult["mch_id"]);
		$appApi->SetPrepayId($UnifiedOrderResult['prepay_id']);
		$appApi->SetPackage("WXPay");
		$appApi->SetNonceStr($UnifiedOrderResult['nonce_str']);
		$timeStamp = time();
		$appApi->SetTimeStamp("$timeStamp");
		$appApi->SetSign();
		return $appApi->GetValues();
	}
}