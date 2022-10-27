<?php
/**
* 财务标签处理类
*
* @version        $Id: financelabel.label.php 2017年4月2日 11:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class financelabel extends finance
{
	static public $lcode;
	static public $data;
	static public $CF = array('finance'=>'GetData');

	function __construct()
	{
		tpl::labelBefore();
		
		//公共url
		self::PublicUrl();
		
		//调用自定义标签
		self::PublicLabel();
	}
	
	
	//公共url替换
	static function PublicUrl()
	{
		$financeConfig = C('',null,'financeConfig');
		$arr = array(
			'卡号购买地址'=>$financeConfig['card_buy_url'],
			'充值比例'=>$financeConfig['rmb_to_gold2'],
		);
		tpl::Rep($arr);
	}
	
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$repFun['t']['financelabel'] = 'PublicLevel';
		tpl::Label('{充值等级:[s]}[a]{/充值等级}','level', self::$CF, $repFun['t']);
		
		//数组键：类名，值：方法名
		$repFun['t']['financelabel'] = 'PublicType';
		tpl::Label('{支付方式:[s]}[a]{/支付方式}','type', self::$CF, $repFun['t']);
	}

	
	/**
	 * 充值等级公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicLevel($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Segmentation(count($data), $i, $lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'充值金额'=>$v['level_money'],
				'充值折扣'=>$v['level_real'],
				'充值赠送金币2'=>$v['level_reward_gold2'],
			);
			//合并两组标签
			$arr = ArrMerge($v,$arr1, $arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	



	/**
	 * 支付方式公共标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicType($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
				
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
	
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'支付方式id'=>$v['api_id'],
				'支付方式名字'=>$v['api_title'],
				'支付方式标识'=>$v['api_name'],
				'支付方式描述'=>$v['api_info'],
			);
			//合并两组标签
			$arr = ArrMerge($v,$arr1, $arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}
	

	/**
	 * 公共提现记录标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicCash($data,$blcode)
	{
		$code = '';
		$page =  C('page.page');
		$pageCount =  C('page.page_count');
		if ( $page > 0 )
		{
			$i = ($page - 1) * $pageCount + 1;
		}
		else
		{
			$i = 1;
		}
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
	
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::IfRep( $v['cash_status'] , '=' , 0 , '处理中' , '' , $lcode);
			$lcode = tpl::IfRep( $v['cash_status'] , '=' , 1 , '已处理' , '' , $lcode);
			$lcode = tpl::IfRep( $v['cash_status'] , '=' , 2 , '已拒绝' , '' , $lcode);
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'提现id'=>$v['cash_id'],
				'提现处理状态'=>parent::GetCashStatus($v['cash_status']),
				'提现申请金额'=>$v['cash_money'],
				'提现实际到账'=>$v['cash_real'],
				'提现申请手续费'=>$v['cash_cost'],
				'提现申请时间'=>date('Y-m-d H:i:s', $v['cash_time']),
				'提现处理时间'=>date('Y-m-d H:i:s', $v['cash_handletime']),
			);
			//合并两组标签
			$arr = array_merge($arr1,$arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
	
		}
	
		//返回最后的结果
		return $code;
	}
	
	
}
?>