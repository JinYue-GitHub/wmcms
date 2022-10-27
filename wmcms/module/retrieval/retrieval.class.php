<?php
/**
* 分类检索模块类文件
*
* @version        $Id: retrieval.class.php 2017年6月18日 16:22  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class retrieval
{
	static $table = '@system_retrieval';
	static $typeTable = '@system_retrieval_type';
	
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
			//type为分类数据获取
			case 'type':
				$wheresql['table'] = self::$typeTable;
				$wheresql['where']['type_status'] = '1';
				break;
				
			//content为内容页数据获取
			case 'content':
				$wheresql['table'] = self::$table;
				$wheresql['left'][self::$typeTable] = 'retrieval_type_id=type_id';
				$wheresql['where']['retrieval_status'] = '1';
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
			'分类模块' =>'type_module',
			'分类类型' =>'type_type',
			'条件' =>'1',
			'排序' =>'2',
			'小说' =>'novel',
			'文章' =>'article',
			'图集' =>'picture',
			'应用' =>'app',
				
	
			'条件排序' =>'retrieval_order',
			'条件顺序' =>'retrieval_order',
			'条件倒序' =>'retrieval_order desc',
			'条件模块' =>'retrieval_module',
			'条件类型' =>'retrieval_type',
		);
		return tpl::GetWhere($where,$arr);
	}
}
?>