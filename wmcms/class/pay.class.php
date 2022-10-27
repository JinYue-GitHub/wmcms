<?php
/**
* 公共支付类
*
* @version        $Id: pay.class.php 2017年7月26日 22:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class pay
{
	//订单号
	public $orderSn;
	//异步、同步返回地址
	public $notifyUrl;
	public $returnUrl;
	//订单标题、订单内容和订单备注信息
	public $subject;
	public $body;
	public $remark;
	//自动加载支付文件地址
	private $autoLoadFile;
	
	function __construct(){}
	
	
	/**
	 * 设置异步通知地址
	 * @param 参数1，必须，异步通知地址
	 */
	function SetNotifyUrl($url='')
	{
		$this->notifyUrl = $url;
	}
	
	/**
	 * 设置同步返回地址
	 * @param 参数1，必须，同步返回地址
	 */
	function SetReturnUrl($url='')
	{
		$this->returnUrl = $url;
	}
	
	/**
	 * 设置订单标题
	 * @param 参数1，必须，订单标题
	 */
	function SetOrderSubJect($subject)
	{
		$this->subject = $subject;
	}
	//获得订单标题
	function GetOrderSubJect()
	{
		if( $this->subject == '' )
		{
			return GetLang('system.finance.order_subject');
		}
		else
		{
			return $this->subject;
		}
	}
	
	/**
	 * 设置订单内容
	 * @param 参数1，必须，订单备注
	 */
	function SetOrderBody($body)
	{
		$this->body = $body;
	}
	//获得订单内容
	function GetOrderBody($money='')
	{
		if( $this->body == '' )
		{
			return GetLang('system.finance.order_body',array('money'=> $money));
		}
		else
		{
			return $this->body;
		}
	}
	
	/**
	 * 设置订单备注
	 * @param 参数1，必须，订单备注
	 */
	function SetOrderRemark($remark)
	{
		$this->remark = $remark;
	}
	//获得订单备注
	function GetOrderRemark($money='')
	{
		if( $this->remark == '' )
		{
			return GetLang('system.finance.order_remark',array('money'=> $money));
		}
		else
		{
			return $this->remark;
		}
	}

	/**
	 * 获得通知参数里面按的支付方式
	 */
	function GetNotifyPayType()
	{
		//还原转义的post参数
		DRequest();
		//微信数据
		$inputData = file_get_contents('php://input');

		//判读POST是否是支付宝/PayPal通知参数
		if( !empty($_POST['passback_params']) )
		{
			$attach = $_POST['passback_params'];
		}
		//PayPal支付
		else if( !empty($_GET['params']) )
		{
			$attach = $_GET['params'];
		}
		//是否是微信回调数据
		else if( $inputData )
		{
			//将XML转为array
			//禁止引用外部xml实体
			libxml_disable_entity_loader(true);
			$inputData = json_decode(json_encode(simplexml_load_string($inputData, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			$attach = $inputData['attach'];
		}
		//否则结束
		else
		{
			return false;
		}

		//url解码参数，并且转位数组。
		$attach = json_decode(urldecode($attach),true);
		//支付方式不存在，或者数据不存在
		if( !$attach || GetKey($attach,'pay_type') == '' )
		{
			return false;
		}
		else
		{
			return $attach['pay_type'];
		}
	}
	
	/**
	 * 检查支付接口自动加载文件
	 * @param 参数1，必须，支付类型
	 */
	function CheckAutoLoadFile($payType)
	{
		$this->autoLoadFile = WMAPI.'pay/'.$payType.'/autoload.php';
		//引如自动加载sdk
		if( !file_exists($this->autoLoadFile) )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	
	/**
	 * 下单操作
	 * @param 参数1，必须，订单基本参数
	 * @param pay_type，必须，支付类型
	 * @param money，必须，充值金额
	 * @param uid，必须，充值的用户
	 * @param account，选填，是否是为好友充值，好友的账号
	 * @param is_first，选填，默认为已经充值过的
	 */
	function Order($data)
	{
		$payType = $data['pay_type'];
		$uid = $data['uid'];
		//充值金额
		$money = $data['money'];
		//实际支付金额
		$realMoney = $money;
		//账号是否已经充值过了
		$isFirst = '1';
		//充值奖励
		$reward = '0';
		if( GetKey($data,'is_first') != '' )
		{
			$isFirst = $data['is_first'];
		}

		//检查自动加载sdk
		if( !$this->CheckAutoLoadFile($payType) )
		{
			return false;
		}
		else
		{
			require_once($this->autoLoadFile);
			//new统一支付类
			$payApi = new UnifiedPay();
			
			//财务配置
			$financeConfig = GetModuleConfig('finance' , true);
			//财务模型
			$orderMod = NewModel('finance.finance_order');
			$levelMod = NewModel('finance.finance_level');
			$userMod = NewModel('user.user');
			
			//是否有充值等级，并且实际支付金额打折了，就设置实际支付金额
			$levelData = $levelMod->GetLastLevel($money);
			if( $levelData )
			{
				$realMoney = $realMoney -($levelData['level_money'] - $levelData['level_real']);
				if( $levelData['level_reward_gold2'] > 0)
				{
					$reward = $levelData['level_reward_gold2'];
				}
			}
			
			//获取订单号
			$orderSn = $orderMod->GetChargeOrderSn($payType);
			$this->orderSn = $orderSn;
			//保存为缓存
			Cookie('order_sn',$orderSn);
		
			//插入站内订单
			$chargeOrder['charge_sn'] = $orderSn;
			$chargeOrder['charge_type'] = $payType;
			$chargeOrder['charge_user_id'] = $uid;
			$chargeOrder['charge_money'] = $realMoney;
			$chargeOrder['charge_gold2'] = $money*$financeConfig['rmb_to_gold2'];
			$chargeOrder['charge_reward'] = $reward;
			//检查是否为好友充值。
			if( !empty($data['account']) )
			{
				$tUserData = $userMod->GetByName($data['account']);
				$chargeOrder['charge_tuser_id'] = $tUserData['user_id'];
				$isFirst = $tUserData['user_ischarge'];
			}
			//没有进行过充值，并且充值奖励大于0
			if( $isFirst == '0' && $financeConfig['recharge_reward_gold2'] > 0 )
			{
				$chargeOrder['charge_first'] = $financeConfig['recharge_reward_gold2'];
			}
			$orderMod->CreateChargeOrder($chargeOrder);
		
			//创建第三方订单信息
			$orderData['sn'] = $orderSn;
			$orderData['subject'] = $this->GetOrderSubJect();
			$orderData['money'] = $realMoney;
			$orderData['body'] = $this->GetOrderBody($realMoney);
			
			//设置异步和同步地址
			$payApi->SetNotifyUrl($this->notifyUrl);
			$payApi->SetReturnUrl($this->returnUrl);
			$result = $payApi->Order($orderData);
			return $result;
		}
	}
	
	

	/**
	 * 异步通知
	 */
	function Notify()
	{
		$payType = $this->GetNotifyPayType();
		if( $payType == false || !$this->CheckAutoLoadFile($payType) )
		{
			return false;
		}
		else
		{
			require_once($this->autoLoadFile);
			$payApi = new UnifiedPay('notify');
			//验证异步签名，并且返回参数
			$result = $payApi->Notify();
			//验证成功。
			if( $result === false )
			{
				return false;
			}
			else
			{
				//完成订单
				return $this->OrderFinish($payType,$result['out_trade_no'],$result['trade_no']);
			}
		}
	}
	

	/**
	 * 同步通知验证
	 */
	function ReturnVer()
	{
		$payType = $this->GetNotifyPayType();
		if( $payType == false || !$this->CheckAutoLoadFile($payType) )
		{
			return false;
		}
		else
		{
			require_once($this->autoLoadFile);
			$payApi = new UnifiedPay('return');
			//验证同步通知，并且返回参数
			$result = $payApi->ReturnVer();
			//验证成功。
			if( $result['code'] == '0' )
			{
				//完成订单
				$this->OrderFinish($payType,$result['out_trade_no'],$result['trade_no']);
			}
			return $result;
		}
	}
	
	
	/**
	 * 设置订单完成。
	 * @param 参数1，必须，支付方式
	 * @param 参数2，必须，站内订单号
	 * @param 参数3，选填，第三方订单号
	 */
	function OrderFinish($payType,$sn,$paySn='0000000000000000000000')
	{
		$orderMod = NewModel('finance.finance_order');
		$orderData = $orderMod->GetChargeOrderBySn($sn);
		//订单不存在，或者不是待支付的订单
		if( !$orderData || $orderData['charge_status'] != '0')
		{
			return false;
		}
		else
		{
			//订单修改为已经支付完成状态。
			$data['charge_paysn'] = $paySn;
			$data['charge_status'] = '1';
			$data['charge_paytime'] = time();
			$orderMod->UpdateChargeOrder($data,$sn);
		
			//用户id
			$uid = $orderData['charge_user_id'];
			//如果给好友充值就改为好友的id
			if( $orderData['charge_tuser_id'] != '0' )
			{
				$uid = $orderData['charge_tuser_id'];
			}
			
			//用户数据
			$userMod = NewModel('user.user');
		
			//写入用户充值金额
			$log['module'] = 'finance';
			$log['type'] = 'charge_'.$payType;
			$log['cid'] = $sn;
			$log['gold2'] = $orderData['charge_gold2'];
			$log['remark'] = $this->GetOrderRemark($orderData['charge_money']);
			if( $orderData['charge_tuser_id'] != '0' )
			{
				$log['tuid'] = $orderData['charge_user_id'];
			}
			$rewardData['gold2'] = $orderData['charge_gold2'];
			$userMod->RewardUpdate( $uid , $rewardData , $log );
		
		
			//检查没有首冲。修改首充状态
			if( $orderData['charge_first'] > '0' )
			{
				$userMod->SaveCharge($uid);
				//写入首充奖励
				$log['type'] = 'charge_first';
				$log['gold2'] = $orderData['charge_first'];
				$log['remark'] = GetLang('system.finance.order_first_remark');
				$rewardData['gold2'] = $orderData['charge_first'];
				$userMod->RewardUpdate( $uid , $rewardData , $log );
			}
		
			//充值金额奖励大于0
			if( $orderData['charge_reward'] > '0' )
			{
				//写入首充奖励
				$log['type'] = 'charge_reward';
				$log['gold2'] = $orderData['charge_reward'];
				$log['remark'] = GetLang('system.finance.order_reward_remark',array('money'=> $orderData['charge_reward']));
				$rewardData['gold2'] = $orderData['charge_reward'];
				$userMod->RewardUpdate( $uid , $rewardData , $log );
			}
			
			return true;
		}
	}
}
?>