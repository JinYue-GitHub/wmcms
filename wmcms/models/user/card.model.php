<?php
/**
* 用户卡号模型
*
* @version        $Id: card.model.php 2017年3月27日 21:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class CardModel
{
	public $table = '@user_card';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 获得卡号的类型
	 * @param 参数1，选填，是否指定返回指定的值
	 */
	function GetCardType($type = '')
	{
		$arr = array(
			'1'=>'充值卡',
			'2'=>'邀请码',
		);
		if( $type != '' )
		{
			return $arr[$type];
		}
		else
		{
			return $arr;
		}
	}
	

	/**
	 * 获得卡号
	 * @param 参数1，必填，卡密
	 * @param 参数2，必填，卡密类型，1为充值卡
	 * @param 参数3，选填，卡密状态，0为没有使用
	 */
	function GetCard($cardKey , $type)
	{
		$where['table'] = $this->table;
		$where['where']['card_key'] = $cardKey;
		$where['where']['card_type'] = $type;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 修改卡号的使用状态
	 * @param 参数1，必须，卡号的id
	 * @param 参数2，选填，是否要修改领取的用户和时间
	 */
	function SetCardStatus($id , $getUid = '')
	{
		$data['card_use']  = 1;
		$data['card_get_time'] = time();
		if( $getUid > 0 )
		{
			$data['card_user_id'] = $getUid;
		}
		$where['card_id'] = $id;
		return wmsql::Update($this->table, $data , $where);
	}
	
	
	
	/**
	 * 使用卡号
	 * @param 参数1，必须 用户id
	 * @param 参数2，必须 卡号数据
	 */
	function UseCard($uid , $option)
	{
		$accountType = $option['accounttype'];
		$account = $option['account'];
		$reAccount = $option['reaccount'];
		$cardKey = $option['cardkey'];
		$toUid = $uid;
		$userMod = NewModel('user.user');
		$config = C('',null,'financeConfig');
		
		$cardData = $this->GetCard($cardKey , 1);
		//卡号不存在
		if( !$cardData )
		{
			return 201;
		}
		//卡号已经使用了
		else if( $cardData['card_use'] == 1 )
		{
			return 202;
		}

		//如果是为好友充值，并且不是自己的账号
		if( $accountType == 1 && user::GetName() != $account)
		{
			$userData = $userMod->GetOne(array('user_name'=>$account));
			//好友帐号不存在
			if( !$userData )
			{
				return 203;
			}
			$toUid = $userData['user_id'];
		}
		
		//修改卡密状态
		$this->SetCardStatus($cardData['card_id'] , $toUid);
		
		//插入卡密使用记录
		$cardLogMod = NewModel('user.card_log');
		$cardLogData['log_card_id'] = $cardData['card_id'];
		$cardLogData['log_user_id'] = $toUid;
		$cardLogMod->Insert($cardLogData);


		//插入充值订单
		$orderMod = NewModel('finance.finance_order');
		$orderData['charge_sn'] = $orderMod->GetChargeOrderSn('card');
		$orderData['charge_paysn'] = $cardData['card_key'];
		$orderData['charge_status'] = 1;
		$orderData['charge_type'] = 'card';
		$orderData['charge_user_id'] = $toUid;
		$orderData['charge_money'] = $cardData['card_money'];
		$orderData['charge_gold2'] = $cardData['card_money']*$config['rmb_to_gold2'];
		$orderData['charge_paytime'] = time();
		$orderData['charge_remark'] = C('user.card_charge_remark',null,'lang');
		$orderMod->CreateChargeOrder($orderData);
		
		
		//插入充值信息
		$chargeMod = NewModel('finance.finance_charge');
		$chargeMod->Charge($toUid , $cardData['card_money'] , 'charge_card' , $cardData);
		return 200;
	}
}
?>