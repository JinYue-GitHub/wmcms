<?php
/**
* 道具标签处理类
*
* @version        $Id: props.label.php 2017年3月17日 18:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @update   	  2018年8月26日 13:26 weimeng
*
*/
class propsLabel extends props
{
	static public $lcode;
	static public $data;
	static public $CF = array('props'=>'GetData');
	
	function __construct()
	{
		self::PublicLabel();
	}
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		$repFun['a']['propslabel'] = 'PublicProps';
		tpl::Label('{道具:[s]}[a]{/道具}','content', self::$CF, $repFun['a']);
		
		$repFun['a']['propslabel'] = 'PublicSell';
		tpl::Label('{道具销售记录:[s]}[a]{/道具销售记录}','sell', self::$CF, $repFun['a']);
		tpl::Label('{道具销售排行:[s]}[a]{/道具销售排行}','selltop', self::$CF, $repFun['a']);
	}


	/**
	* 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	**/
	static function PublicProps($data,$blcode)
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
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'道具id'=>$v['props_id'],
				'道具名字'=>$v['props_name'],
				'道具图标'=>$v['props_cover'],
				'道具库存'=>$v['props_stock'],
				'道具消费类型'=>$v['props_cost'],
				'道具介绍'=>$v['props_desc'],
				'道具价格1'=>$v['props_gold1'],
				'道具价格2'=>$v['props_gold2'],
				'道具售价'=>$v['props_money'],
			);

			//合并两组标签
			$arr = array_merge($arr1 , $arr2);
			//替换标签
			$code .= tpl::rep($arr,$lcode);
			
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	

	/**
	 * 公共道具销售记录替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicSell($data,$blcode)
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

			$time = tpl::Tag('{道具销售记录时间:[s]}',$lcode);
			$remarkArr = tpl::Exp('{道具销售备注:[d]}' , $v['sell_remark'] , $lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'道具销售id'=>$v['sell_id'],
				'道具销售金币1合计'=>GetKey($v,'sell_sum1'),//在道具销售排行的标签下才有值
				'道具销售金币2合计'=>GetKey($v,'sell_sum2'),//在道具销售排行的标签下才有值
				'道具销售道具id'=>$v['props_id'],
				'道具销售名字'=>$v['props_name'],
				'道具销售图标'=>$v['props_cover'],
				'道具销售数量'=>$v['sell_number'],
				'道具销售价格1'=>$v['sell_gold1'],
				'道具销售价格2'=>$v['sell_gold2'],
				'道具销售价格'=>$v['sell_money'],
				'道具销售备注'=>$v['sell_remark'],
				'道具销售时间'=>@date("Y-m-d H:i:s",$v['sell_time']),
				'道具销售时间戳'=>$v['sell_time'],
				'道具销售用户昵称'=>$v['user_nickname'],
				'道具销售用户头像'=>$v['user_head'],

				'道具销售备注:'.GetKey($remarkArr,'0')=>GetKey($remarkArr,'1'),
				'道具销售时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['sell_time']),
			);
			//合并两组标签
			$arr = array_merge($arr1 , $arr2);
			//替换标签
			$code .= tpl::rep($arr,$lcode);
				
			$i++;
		}
		//返回最后的结果
		return $code;
	}
}
?>