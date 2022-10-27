<?php
/**
* 关于信息系统类文件
*
* @version        $Id: about.class.php 2015年10月05日 21:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月18日 14:52 weimeng
*
*/
class about
{
	function __construct()
	{
		//调用标签构造函数
		if (class_exists('aboutlabel'))
		{
			new aboutlabel();
		}
	}


	/**
	* 根据所得到的条件查询数据
	* @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	* @param 参数2，传递的sql条件
	* @param 参数3，选填，没有数据的提示字符串
	**/
	static function GetData($type,$where='',$errInfo='')
	{
		$wheresql = self::GetWhere($where);
		
		//type为列表页数据获取
		switch ($type)
		{
			//列表页获取
			case 'type':
				$wheresql['table']['@about_type'] = 't';
				$wheresql['field'] = 't.*';
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['table']['@about_about'] = 'a';
				$wheresql['left']['@about_type as t'] = 'a.type_id =t.type_id';
				$wheresql['field'] = 'a.*,t.*';
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
			'父级分类' =>'type_topid',

			'id' =>'about_id',
			'aid' =>'about_id',
			'about_id' =>'about_id',
			'apinyin' =>'about_pinyin',
			'顺序' =>'about_order',
			'倒序' =>'about_order desc',
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
			case 'type':
				$parName['id'] = 't.type_id';
				$parName['pinyin'] = 't.type_pinyin';
				break;

			case 'content':
				$parName['id'] = 'about_id';
				$parName['pinyin'] = 'about_pinyin';
				break;
		}

		return CheckPar(  $parName , $par , $where );
	}
}
?>