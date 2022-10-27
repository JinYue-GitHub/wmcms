<?php
/**
* 留言系统类文件
*
* @version        $Id: message.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月5日 20:05 weimeng
*
*/
class message
{
	function __construct()
	{
		//调用标签构造函数
		new messagelabel();
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
			//列表页获取
			case 'type':
				$wheresql['table']['@app_type'] = 't';
				break;
			
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
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
			'tid' =>'t.type_id',
			'type_id' =>'t.type_id',
			'tpinyin' =>'t.type_pinyin',
			'分类排序' =>'type_order',
			'分类顺序' =>'t.type_id',
			'分类倒序' =>'t.type_id desc',
		);

		return tpl::GetWhere($where,$arr);
	}
}
?>