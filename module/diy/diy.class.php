<?php
/**
* 自定义页面类文件
*
* @version        $Id: diy.class.php 2015年9月19日 13:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月31日 10:00
*
*/
class diy
{
	function __construct()
	{
		new diylabel();
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
				$wheresql['field'] = '*';
				$wheresql['table']= '@diy_diy';
				$wheresql['where']['diy_status'] = "1";
				
				//分页处理
				if( GetKey($wheresql,'list') )
				{
					page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
				}
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
			'tid' =>'diy_type_id',
			'分类' =>'diy_type_id',
			'id' =>'diy_id',
			'pinyin' =>'diy_pinyin',
			'时间'=>'diy_time desc',
		);
	
		return tpl::GetWhere($where,$arr);
	}
	
	
	/**
	 * url参数匹配
	 * @param 参数1，必填，当前页面参数的类型
	 * @param 参数2，必填，参数的值。
	 */
	static function GetPar( $type , $par , $where = array()){
		//参数是否为数字的变量。
		switch ($type)
		{
	
			case 'content':
				$parInt = 0;
				$parName['id'] = 'diy_id';
				$parName['pinyin'] = 'diy_pinyin';
				break;
		}

		return CheckPar(  $parName , $par , $where );
	}
}
?>