<?php
/**
* 专题类文件
*
* @version        $Id: zt.class.php 2015年9月19日 11:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月31日 12:00
*
*/

class zt
{
	function __construct()
	{
		new ztlabel();
	}


	/**
	 * 根据所得到的条件查询数据
	 * @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	 * @param 参数2，传递的sql条件
	 * @param 参数3，选填，没有数据的提示字符串
	 **/
	static function GetData( $type , $where='' , $errInfo='' )
	{
		$wheresql = self::GetWhere($where,$type);
	
		//type为列表页数据获取
		switch ($type)
		{
			//列表页获取
			case 'type':
				$wheresql['table']['@zt_type'] = 't';
				$wheresql['field'] = 't.*';
				break;
				
			//content为内容页数据获取
			case 'content':
				$wheresql['field'] = '*';
				$wheresql['table']['@zt_zt'] = 'a';
				$wheresql['left']['@zt_type as t'] = 't.type_id=a.type_id';
				$wheresql['where']['zt_status'] = "1";
	
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

		//如果数组为空并且错误提示不为空则输出错误提示。
		if( $type == 'type' && ( GetKey($where,'t.type_id') == '0' || GetKey($where,'t.type_pinyin') == 'all') )
		{
			$data[0] = array(
				'type_name'=>'全部分类',
				'type_cname'=>'全部',
				'type_id'=>'0',
				'type_pid'=>'0',
				'type_topid'=>'0',
				'type_pinyin'=>'all',
				'type_info'=>'',
				'type_title'=>'',
				'type_key'=>'',
				'type_desc'=>'',
			);
		}
		else if( !$data && $errInfo != '' )
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
	static function GetWhere($where,$type='type')
	{
		$typeField = 't.type_id';
		if( $type == 'content' )
		{
			$typeField = 'a.type_id';
		}
		//设置需要替换的字段
		$arr = array(
			'tid' =>$typeField,
			'分类' =>$typeField,
			'tpinyin' =>'t.type_pinyin',
			'分类拼音' =>'t.type_pinyin',
			'id' =>'zt_id',
			'专题id' =>'zt_id',
			'pinyin' =>'zt_pinyin',
			'专题拼音' =>'zt_pinyin',
			'时间'=>'zt_time desc',
		);
	
		return tpl::GetWhere( $where , $arr );
	}
	
	
	/**
	 * url参数匹配
	 * @param 参数1，必填，当前页面参数的类型
	 * @param 参数2，必填，参数的值。
	 */
	static function GetPar( $type , $par , $where = array() )
	{
		//参数是否为数字的变量。
		switch ($type)
		{
			case 'type':
				$parName['id'] = 't.type_id';
				$parName['pinyin'] = 't.type_pinyin';
				break;
				
			case 'content':
				$parName['id'] = 'zt_id';
				$parName['pinyin'] = 'zt_pinyin';
				break;
		}

		return CheckPar(  $parName , $par , $where );
	}
}
?>