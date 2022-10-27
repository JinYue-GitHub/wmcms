<?php
/**
* 粉丝数据标签处理类
*
* @version        $Id: fans.label.php 2018年8月26日 13:26  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class fansLabel extends fans
{
	static public $lcode;
	static public $data;
	static public $CF = array('fans'=>'GetData');
	
	function __construct()
	{
		self::PublicLabel();
	}
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		$repFun['a']['fanslabel'] = 'PublicFans';
		tpl::Label('{粉丝:[s]}[a]{/粉丝}','fans', self::$CF, $repFun['a']);
		
		$repFun['a']['fanslabel'] = 'PublicConsume';
		tpl::Label('{粉丝消费记录:[s]}[a]{/粉丝消费记录}','consume', self::$CF, $repFun['a']);
	}


	/**
	* 公共粉丝标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	**/
	static function PublicFans($data,$blcode)
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

			$time = tpl::Tag('{粉丝关注时间:[s]}',$lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'粉丝id'=>$v['fans_user_id'],
				'粉丝昵称'=>$v['user_nickname'],
				'粉丝头像'=>$v['user_head'],
				'粉丝经验值'=>$v['fans_exp'],
				'粉丝关注时间'=>date("Y-m-d H:i:s",$v['fans_addtime']),
				'粉丝关注时间戳'=>$v['fans_addtime'],
				'粉丝关注时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['fans_addtime']),
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
	 * 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicConsume($data,$blcode)
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
				'粉丝消费用户id'=>$v['consume_user_id'],
				'粉丝消费用户昵称'=>$v['user_nickname'],
				'粉丝消费用户头像'=>$v['user_head'],
				'粉丝消费金币1'=>$v['consume_gold1'],
				'粉丝消费金币2'=>$v['consume_gold2'],
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