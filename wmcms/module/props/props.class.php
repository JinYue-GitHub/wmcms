<?php
/**
* 道具功能模块类文件
*
* @version        $Id: props.class.php 2017年3月17日 18:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @update   	  2018年8月26日 13:26 weimeng
*
*/
class props
{
	static $typeTable = '@props_type';
	static $propsTable = '@props_props';
	static $sellTable = '@props_sell';
	static protected $module = '';
	static protected $cid = '';
	
	//构造方法
	function __construct($data=array())
	{
		if( $data )
		{
			self::$module = $data['module'];
			self::$cid = $data['cid'];
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
		//type为列表页数据获取
		switch ($type)
		{
			//content为内容页数据获取
			case 'content':
				$wheresql['table'] = self::$typeTable;
				$wheresql['left'][self::$propsTable] = 'type_id=props_type_id';
				$wheresql['where']['props_status'] = '1';
				break;
				
			//sell为销售记录获取
			case 'sell':
				$wheresql['table'] = self::$sellTable;
				$wheresql['field'] = 'props_id,props_name,props_cover,sell_id,sell_number,sell_gold1,sell_gold2,
						sell_money,sell_remark,sell_time,user_nickname,user_head';
				$wheresql['left'][self::$propsTable] = 'props_id=sell_props_id';
				$wheresql['left'][user::$userTabel] = 'sell_user_id=user_id';
				$wheresql['where']['sell_module'] = self::$module;
				$wheresql['where']['sell_cid'] = self::$cid;
				break;
				
			//selltop为销售排行获取
			case 'selltop':
				$wheresql['table'] = self::$sellTable;
				$wheresql['field'] = 'SUM(sell_gold1) as sell_sum1,SUM(sell_gold2) as sell_sum2,props_id,props_name,props_cover,sell_id,sell_number,sell_gold1,sell_gold2,
						sell_money,sell_remark,sell_time,user_nickname,user_head';
				$wheresql['left'][self::$propsTable] = 'props_id=sell_props_id';
				$wheresql['left'][user::$userTabel] = 'sell_user_id=user_id';
				$wheresql['where']['sell_module'] = self::$module;
				$wheresql['where']['sell_cid'] = self::$cid;
				$wheresql['group'] = 'sell_user_id,sell_module,sell_cid';
				break;

			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}

		$data = wmsql::GetAll($wheresql);

		//如果数组为空并且错误提示不为空则输出错误提示。
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
			'tid' =>'type_id',
			'type_id' =>'type_id',
			'分类' =>'type_id',
			'分类排序' =>'type_order',
			'分类顺序' =>'type_order',
			'分类倒序' =>'type_order desc',
			'父级分类' =>'type_topid',
			'模块' =>'type_module',
			'小说' =>'novel',

			'顺序' =>'props_order',
			'道具' =>'props_order desc',
			'销售倒序' =>'sell_id desc',
			'销售排行金币1倒序' =>'sell_sum1 desc',
			'销售排行金币2倒序' =>'sell_sum2 desc',
		);
		return tpl::GetWhere($where,$arr);
	}
}
?>