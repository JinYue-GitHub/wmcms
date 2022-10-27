<?php
/**
* 财务系统类文件
*
* @version        $Id: finance.class.php 2017年4月5日 11:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月29日 9:14 weimeng
*
*/
class finance
{
	static $levelTable = '@finance_level';
	static $typeTable = '@api_api';
	static $cashTable = '@finance_order_cash';
	/**
	 * 构造函数
	 * @param 参数1，选填，是否自动载入标签类
	 * @param string $labelLoad
	 */
	function __construct( $labelLoad = true )
	{
		if( $labelLoad )
		{
			//调用标签构造函数
			new financelabel();
		}
	}


	/**
	* 根据所得到的条件查询数据
	* @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	* @param 参数2，传递的sql条件
	* @param 参数3，选填，没有数据的提示字符串
	**/
	static function GetData( $type , $where='' , $errInfo='' )
	{
		$wheresql = self::GetWhere($where);
		switch ($type)
		{
			//充值等级获取
			case 'level':
				$wheresql['table'] = self::$levelTable;
				$wheresql['order'] = 'level_money asc';
				break;
				
			//充值类型获取
			case 'type':
				$wheresql['table'] = self::$typeTable;
				$wheresql['where']['api_open'] = '1';
				$wheresql['order'] = 'api_order asc';
				break;
				
			//充值类型获取
			case 'cash_list':
				$wheresql['table'] = self::$cashTable;
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}

		//分页处理
		if( isset($wheresql['list']) )
		{
			page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
		}
		$data = wmsql::GetAll($wheresql);

		if( !$data && $errInfo != '' )
		{
			tpl::ErrInfo($errInfo);
		}
		return $data;
	}


	/**
	* 获得字符串中的条件sql
	* 返回值字符串
	* @param 参数1：需要查询的字符串。
	**/
	static function GetWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'支付类型'=>'type_id',
			'pc支付'=>'3',
			'wap支付'=>'6',
			'所有支付'=>'[or->3,6]',
		);

		return tpl::GetWhere($where,$arr);
	}
	

	/**
	 * 获得提现申请状态
	 */
	static function GetCashStatus( $status = '' )
	{
		switch ($status)
		{
			case '1':
				return '已通过';
				break;
				
			case '2':
				return '已拒绝';
				break;
				
			default:
				return '处理中';
				break;
		}
	}
}
?>